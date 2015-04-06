<?php
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
	 * holds the configuration
	 * 
	 * @var array
	 */
	private $data = array();


	/**
	 * Iterator Access Counter
	 * 
	 * @var int
	 */
	private $iteratorCount = 0;


	/**
	 * Constructor
	 * Populates the data array with the values injected at runtime
	 *
	 * @param mixed $data The raw input data
	 */
	public function __construct($data)
	{

		// if we've got an array, use it, else, we assume this is json.
	#	$this->data = (is_array($data) ? $data : $this->parse($data));
		$this->add($data);
	}
  
  
	/**
	 * parses the input given as a json string
	 * 
	 * @param string $input the JSON string
	 * @return array the parsed data
	 */
	private function parse($input)
	{
		$data = json_decode($input, true);
		if ($error = json_last_error() != 0) {
			throw new \Exception('Error while parsing JSON: ' . $error);
		}

		return $data;
	}


	/**
	 * merges the config data with another array
	 * 
	 * @param string|array $input the data to add
	 * @return void
	 */
	public function add($input)
	{
		// determine if array or path given
		if (is_string($input)) {
			if (is_dir($input)) {
				if (! is_readable($input)) throw new \Exception('Directory is not readable.');
				
				// add each file in a directory to the config
				foreach (array_diff(scandir($input), array('..', '.')) as $file) {
					$this->add($file);
				}
				
				// break out
				return;
			} else if (is_file($input)) {
				if (! is_readable($input)) throw new \Exception('File is not readable.');
				
				$this->add($this->parse(file_get_contents($input)));
				
				// break out
				return;
			} else {
				// attempt to parse input
				$this->add($this->parse($input));
			}
		}
		// merge the arrays 
		$this->data = array_replace_recursive($this->data, $input);
	}

	/**
	 * gets a value from the config array
	 *
	 * @param string $key (optional) the config key in question
	 * @param mixed $fallback (optional) a fallback value in case the config is empty
	 * @return the value of $data[$key]
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
	 * checks wether a key is set or not
	 *
	 * @param string $key  the config key in question
	 * @return bool wether the key exists or not
	 */
	public function has($key)
	{
		return (! is_null($this->get($key))) ? true : false;	
	}


	/**
	 * sets a value in the config array
	 * 
	 * @param string $key the config key in question
	 * @param mixed $value the value to set 
	 * @return void;
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
	 * erases a key from the array
	 *
	 * @param string $key the config key in question
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
	 * returns the complete config array as serialized data.
	 * Mainly for debug purposes, but what do I know? I'm just a comment.
	 * 
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
	public function offsetSet($offset, $value)
	{
		if (is_null($offset)) {
			$this->data[] = $value;
		} else {
			$this->set($offset, $value);
		}
	}

	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

	public function offsetGet($offset)
	{
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}
	
	
	/**
	* Iterator Interface
	*
	*/
	public function rewind()
	{
		$this->iteratorCount = 0;
	}
	
	public function current()
	{
		$values = array_values($this->data);
	return $values[$this->iteratorCount];
	}
	
	public function key()
	{
		$keys = array_keys($this->data);
		return $keys[$this->iteratorCount];
	}
	
	public function next()
	{
		$this->iteratorCount++;
	}
	
	public function valid()
	{
		$values = array_values($this->data);
		return (isset($values[$this->iteratorCount]));
	}
	
	/**
	* Count Interface
	*
	*/
	public function count()
	{
		return count($this->data);
	}
}
