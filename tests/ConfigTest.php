<?php

/**
 * Config Test
 *
 */
class ConfigTest extends PHPUnit_Framework_TestCase
{
  private $fixturePath = '';

  function setUp()
  {
    parent::setUp();
    $this->fixturePath = dirname(__FILE__) . '/fixtures/';
  }

  public function testCreateObjectWithArrayAsParameter()
  {
    $array = array('a', 'b', 'c');
    $obj = new Radiergummi\Libconfig\Config($array);
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Config', $obj);
  }

  public function testGetValueFromConfig() 
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\Libconfig\Config($array);

    $this->assertEquals('foo', $obj->get('a'));
  }

}
