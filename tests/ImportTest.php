<?php

/**
 * Config Import Test
 *
 */
class ImportTest extends PHPUnit_Framework_TestCase
{
  private $fixturePath = '';

  function setUp()
  {
    parent::setUp();
    $this->fixturePath = dirname(__FILE__) . '/fixtures/';
  }

  public function testCreateObjectWithJSONFileAsParameter()
  {
    $file = $this->fixturePath . 'json/datatypes.json';
    $obj = new Radiergummi\Libconfig\Config(require($file));
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Config', $obj);

    $this->assertEquals('test', $obj->get('a'));
  }

  public function testCreateObjectWithMalformedJSONFileAsParameter()
  {
    $file = $this->fixturePath . 'json/broken.json';
    $obj = new Radiergummi\Libconfig\Config(require($file));
    
    $this->setExpectedException('\Exception');
  }

  public function testCreateObjectWithPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/datatypes.php';
    $obj = new Radiergummi\Libconfig\Config(require($file));
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Config', $obj);

    $this->assertEquals('test', $obj->get('a'));
  }

  public function testCreateObjectWithMalformedPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/broken.php';
    $obj = new Radiergummi\Libconfig\Config(require($file));
    
    $this->setExpectedException('\Exception);
  }
}
