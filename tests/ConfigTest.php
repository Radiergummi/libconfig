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
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Config', $obj);
  }

  public function testGetValue() 
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\Libconfig\Config($array);

    $this->assertEquals('foo', $obj->get('a'));
  }

  public function testGetNestedValue()
  {
    $json = '{ "a": "foo", "b": { "sub": { "key": "value" } } }';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $this->assertEquals('value', $obj->get('b.sub.key'));
  }

  public function testGetWholeConfig()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');
    $obj = new Radiergummi\Libconfig\Config($array);
    
    $this->assertEquals($array, $obj->get());
  }

  public function testObjectHasValue()
  {
    $json = '{"a": "foo", "b": "bar", "c": "baz"}';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $this->assertTrue($obj->has('a'));
  }

  public function testEraseKeyFromObject()
  {
    $json = '{"a": "foo", "b": "bar", "c": "baz"}';
    $obj = new Radiergummi\Libconfig\Config($json);

    $this->assertTrue($obj->has('a'));

    $obj->erase('a');
    
    $this->assertFalse($obj->has('a'));
  }

  public function testSetValue()
  {
    $json = '{"a": "foo", "b": "bar", "c": "baz"}';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $obj->set('key', 'value');
    
    $this->assertEquals('value', $obj->get('key'));
  }

  public function testSetNestedValue()
  {
    $json = '{"a": "foo", "b": "bar", "c": "baz"}';
    $obj = new Radiergummi\Libconfig\Config($json);
    
    $obj->set('key.sub.stuff', 'content');
    
    $this->assertEquals('content', $obj->get('key.sub.stuff'));
  }
  

  public function testAddNewArray()
  {
    $first = '{"a": "foo", "b": "bar", "c": "baz"}';
    $second = array('b' => 'notbar', 'a' => 'foo', 'jack' => 'hughes');

    $obj = new Radiergummi\Libconfig\Config($first);
    
    $obj->add($second);
    var_dump($obj->get());
    $this->assertEquals('notbar', $obj->get('b'));
  }

  public function testToString()
  {
    $array = array('a' => 'foo', 'b' => 'bar', 'c' => 'baz');

    $obj = new Radiergummi\Libconfig\Config($array);
    
    $this->assertEquals($array, unserialize($obj));
  }
  
  
}
