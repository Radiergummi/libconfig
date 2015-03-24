<?

/**
 * General purpose config class
 *
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
	 * @param string $input the JSON string
	 * @return array the parsed data
	 */
	public function add($input) {
		$data = (is_array($input) ? $input : $this->parse($input));
		$this->data = array_replace_recursive($data, $this->data);
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
}
