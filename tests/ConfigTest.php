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

  public function testCreateObjectWithJSONAsParameter()
  {
    $json = '{"a": "foo", "b": "bar", "c": "baz"}';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $this->assertEquals('foo', $obj->get('a'));
  }

  public function testGetValueFromConfig() 
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\Libconfig\Config($array);

    $this->assertEquals('foo', $obj->get('a'));
  }

  public function testGetNestedValueFromConfig()
  {
    $json = '{ "a": "foo", "b": { "sub": { "key": "value" } } }';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $this->assertEquals('value', $obj->get('b.sub.key'));
  }

  public function testObjectHasValue()
  {
    $json = {"a": "foo", "b": "bar", "c": "baz" }
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $this->assertTrue($obj->has('a'));
  }

  public function testEraseKeyFromObject()
  {
    $json = {"a": "foo", "b": "bar", "c": "baz" }
    $obj = new Radiergummi\Libconfig\Config($json);

    $this->assertTrue($obj->has('a'));

    $obj->erase('a');
    
    $this->assertFalse($obj->has('a'));
  }

}
