<?php
// Advice: change namespace to "<your app>\lib" 
namespace Radiergummi\libconfig;

use ArrayAccess;
use Iterator;
use Countable;

/**
 * General purpose config class
 * 
 * @package php-config
 * @author Moritz Friedrich <m@9dev.de>
 */
class Config implements \ArrayAccess, \Iterator, \Countable
{
  /**
   * holds the configuration data
   * 
   * (default value: array())
   * 
   * @var array
   * @access private
   */
  private $data = array();


  /**
   * Iterator Access Counter
   * 
   * (default value: 0)
   * 
   * @var int
   * @access private
   */
  private $iteratorCount = 0;


  /**
   * __construct function.
   * Populates the data array with the values injected at runtime
   * 
   * @access public
   * @param mixed $data  The raw input data
   * @return void
   */
  public function __construct($data)
  {
    $this->add($data);
  }
  

  /**
   * parseJSON function.
   * parses the input given as a json string
   * 
   * @access private
   * @param mixed $input  a JSON string
   * @return array $data  the parsed data
   */
  private function parseJSON($input)
  {
    // JSON error message strings
    $errorMessages = array(
      'No error has occurred',
      'The maximum stack depth has been exceeded',
      'Invalid or malformed JSON',
      'Control character error, possibly incorrectly encoded',
      'Syntax Error',
      'Malformed UTF-8 characters, possibly incorrectly encoded'
    );

    // decode JSON and throw exception on error
    $data = json_decode($input, true);
    if (($error = json_last_error()) != 0) {
      throw new \RuntimeException('Error while parsing JSON: ' . $errorMessages[$error] . ' ("' . substr($input, 0, 20) . '...")');
    }

    return $data;
  }


  /**
   * add function.
   * merges the config data with another array
   * 
   * @access public
   * @param string|array $input  the data to add
   * @return void
   */
  public function add($input)
  {
    // determine if array or path given
    if (is_string($input)) {
      if (is_dir($input)) {
        if (! is_readable($input)) throw new \RuntimeException('Directory is not readable.');

        // add each file in a directory to the config
        foreach (array_diff(scandir($input), array('..', '.')) as $file) {
          $this->add(rtrim($input, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file);
        }

        return;
      } else if (is_file($input)) {
        if (! is_readable($input)) throw new \RuntimeException('File is not readable.');

          switch(pathinfo($input, PATHINFO_EXTENSION)) {
            case 'php':
              $content = require($input);
              break;
  
            case 'json':
              $content = $this->parseJSON(file_get_contents($input));
              break;
              
            // example INI implementation
            #case 'ini':
            #  $content = $this->parseINI(file_get_contents($input));
            #  break;
          }
        
        $this->add($content);

        return;
      } else {

        // string input is generally treated as JSON
        $this->add($this->parseJSON($input));

        return;
      }
    }

    if (is_array($input)) $this->data = array_replace_recursive($this->data, $input);

    return;
  }


  /**
   * get function.
   * gets a value from the config array
   * 
   * @access public
   * @param mixed $key (default: null)  the config key in question
   * @param mixed $fallback (default: null)  a fallback value in case the config is empty
   * @return string  the value of $data[$key]
   */
  public function get($key = null, $fallback = null)
  {

    // return the whole config if no key specified
    if (! $key) return $this->data;

    $keys = explode('.', $key);
    $values = $this->data;

    if (count($keys) == 1) {

      return (array_key_exists($keys[0], $values) ? $values[$keys[0]] : $fallback);
    } else {

      // search the array using the dot character to access nested array values
      foreach($keys as $key) {

        // when a key is not found or we didnt get an array to search return a fallback value
        if(! array_key_exists($key, $values)) {

          return $fallback;
        }

        $values =& $values[$key];
      }

      return $values;
    }
  }

  /**
   * has function.
   * checks wether a key is set
   * 
   * @access public
   * @param string $key  the config key in question
   * @return bool  wether the key is set
   */
  public function has($key)
  {
    return (! is_null($this->get($key))) ? true : false;  
  }


  /**
   * set function.
   * sets a value in the config array
   * 
   * @access public
   * @param string $key  the config key in question
   * @param mixed $value  the value to set 
   * @return void
   */
  public function set($key, $value)
  {
    $array =& $this->data;
    $keys = explode('.', $key);

    // traverse the array into the second last key
    while(count($keys) > 1) {
      $key = array_shift($keys);

      // make sure we have an array to set our new key in
      if( ! array_key_exists($key, $array)) {
        $array[$key] = array();
      }
      $array =& $array[$key];
    }
    $array[array_shift($keys)] = $value;
  }


  /**
   * erase function.
   * 
   * @access public
   * @param mixed $key
   * @return void
   */
  public function erase($key)
  {
    $array =& $this->data;
    $keys = explode('.', $key);
    // traverse the array into the second last key
    while(count($keys) > 1) {
      $key = array_shift($keys);
      $array =& $array[$key];
    }
    unset($array[array_shift($keys)]);
  }


  /**
  * __tostring function.
  * returns the complete config array as serialized data.
  * 
  * @access public
  * @return array $data the complete config array as serialized data
  */
  public function __tostring()
  {
    return serialize($this->data);
  }
  
  /**
   * ArrayAccess Interface
   * 
   */

  /**
   * offsetSet function.
   * 
   * @access public
   * @param mixed $offset
   * @param mixed $value
   * @return void
   */
  public function offsetSet($offset, $value)
  {
    if (is_null($offset)) {
      $this->data[] = $value;
    } else {
      $this->set($offset, $value);
    }
  }


  /**
   * offsetExists function.
   * 
   * @access public
   * @param mixed $offset
   * @return void
   */
  public function offsetExists($offset)
  {
    return isset($this->data[$offset]);
  }


  /**
   * offsetUnset function.
   * 
   * @access public
   * @param mixed $offset
   * @return void
   */
  public function offsetUnset($offset)
  {
    unset($this->data[$offset]);
  }


  /**
   * offsetGet function.
   * 
   * @access public
   * @param mixed $offset
   * @return bool
   */
  public function offsetGet($offset)
  {
    return isset($this->data[$offset]) ? $this->data[$offset] : null;
  }
  
  
  /**
  * Iterator Interface
  *
  */

  /**
   * rewind function.
   * 
   * @access public
   * @return void
   */
  public function rewind()
  {
    $this->iteratorCount = 0;
  }


  /**
   * current function.
   * 
   * @access public
   * @return mixed
   */
  public function current()
  {
    $values = array_values($this->data);

    return $values[$this->iteratorCount];
  }


  /**
   * key function.
   * 
   * @access public
   * @return mixed
   */
  public function key()
  {
    $keys = array_keys($this->data);

    return $keys[$this->iteratorCount];
  }


  /**
   * next function.
   * 
   * @access public
   * @return void
   */
  public function next()
  {
    $this->iteratorCount++;
  }


  /**
   * valid function.
   * 
   * @access public
   * @return bool
   */
  public function valid()
  {
    $values = array_values($this->data);

    return (isset($values[$this->iteratorCount]));
  }



  /**
  * Count Interface
  *
  */

  /**
   * count function.
   * 
   * @access public
   * @return int
   */
  public function count()
  {
    return count($this->data);
  }
}
