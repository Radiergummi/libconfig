<?

/**
 * General purpose config class
 * 
 * @package php-config
 * @author Moritz Friedrich <m@9dev.de>
 */
class Config {

	/**
	 * holds the configuration
	 * 
	 * @var data
	 */
	private $data = array();


	/**
	 * Constructor
	 * Populates the data array with the values injected at runtime
	 *
	 * @param mixed $data The raw input data
	 */
	public function __construct($data) {
	  $this->data = (is_array($data) ? $data : $this->parse($data));
	}
  
  
	/**
	 * parses the input given as a json string
	 * 
	 * @param string $input the JSON string
	 * @return array the parsed data
	 */
	private function parse($input) {
	  $data = json_decode($input, true);
	  if ($error = json_last_error() != 0) {
	  	throw new Exception('Error while parsing JSON: ' . $error);
	  }

	  return $data;
	}


	/**
	 * merges the config data with another array
	 * 
	 * From: http://www.php.net/manual/en/function.array-merge-recursive.php#102379
	 * @param string $input the JSON string
	 * @return void
	 */
	public function add($input, $toMerge = null) {
		// if the function is called from within itself, use the array provided as a parameter
		$arr1 = (is_array($toMerge) ? $toMerge : $this->$data);
		$arr2 = (is_array($input) ? $input : $this->parse($input));

		foreach ($arr2 as $key => $value) {
			if (array_key_exists($key, $arr1) && is_array($value)) {
				$arr1[$key] = $this->add($arr2[$key], $arr1[$key]);
			} else {
				$arr1[$key] = $value;
			}
		}
		$this->$data = $arr1;
	}


	/**
	 * gets a value from the config array
	 *
	 * @param string $key the config key in question
	 * @param mixed $fallback (optional) a fallback value in case the config is empty
	 * @return the value of $data[$key]
	 */
	public function get($key, $fallback = null) {
		$keys = explode('.', $key);
		$values = $this->data;
		if (count($keys) == 1) {
			$value = $values[$keys[0]];
			return (!empty($value) ? $value : $fallback);
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
	 * sets a value in the config array
	 * 
	 * @param string $key the config key in question
	 * @param mixed $value the value to set 
	 * @return void;
	 */
	public function set($key, $value) {
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
	 * returns the complete config array. Mainly for debug purposes, but what do I know? I'm just a comment.'
	 * 
	 * @return array $data the complete config array
	 */
	 public function __tostring() {
	 	return $this->data;
	 }


	/**
	 * returns the complete config array. Mainly for debug purposes, but what do I know? I'm just a comment.'
	 * 
	 * @return array $data the complete config array
	 */
	 public function __callStatic($method, $arguments) {
	 	return $this->$method(implode(', ', $arguments));
	 }
}
