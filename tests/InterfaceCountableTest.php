<?php

/**
 * Config Test
 *
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
  public function testCountableInterfaceSucceeds()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\libconfig\Config($array);
    $this->assertEquals(3, count($obj));
  }
}
