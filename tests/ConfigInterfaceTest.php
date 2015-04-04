<?php

/**
 * Config Interface Test
 *
 * Tests all implemented interfaces
 */
class ConfigInterfaceTest extends PHPUnit_Framework_TestCase
{
  public function testCountableInterface()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\libconfig\Config($array);

    $this->assertEquals(3, count($obj));
  }

  public function testCountableInterfaceWithNestedArray()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => array('sub1' => 'value1', 'sub2' => 'value2', 'sub3' => 'value3'));
    $obj = new Radiergummi\libconfig\Config($array);

    $this->assertEquals(3, count($obj, true));
  }
  
  
  
  public function testIteratorInterface()
  {
    $json = '{ "a": "value", "b": ["foo", "bar", "baz"], "c": { "key1": "value1", "key2": ["a", "b", "c"], "key3": { "suba": "valuea", "subb": "valueb", "subc": "valuec" } } }';
    $obj = new Radiergummi\libconfig\Config($json);

    $actualArr = array();
    foreach($obj as $key => $val) {
        $actualArr[$key] = $val;
    }        
    $expectedArr = array(
        'a' => 'value',
        'b' => array('foo', 'bar', 'baz'),
        'c' => array(
          'key1' => 'value1',
          'key2' => array('a', 'b', 'c'),
          'key3' => array(
            'suba' => 'valuea',
            'subb' => 'valueb',
            'subc' => 'valuec'
            )
        )
    );

    $this->assertEquals($expectedArr, $actualArr);
  }
  
  
  

  public function testArrayAccessInterface()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz', 'd' => array(1, 2, 3));
    $obj = new Radiergummi\libconfig\Config($array);

    $this->assertEquals('foo', $obj['a']);   

    $this->assertEquals(array(1, 2, 3), $obj['d']);
  }
}
