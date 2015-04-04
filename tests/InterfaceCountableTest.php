<?php

/**
 * Config Test
 *
 */
class ConfigCountableTest extends PHPUnit_Framework_TestCase
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
}
