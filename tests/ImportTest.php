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
    $obj = new Radiergummi\Libconfig\Libconfig($file);
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Libconfig', $obj);

    echo PHP_EOL . PHP_EOL; print_r($obj->get()); echo PHP_EOL . PHP_EOL;
    $this->assertEquals('test', $obj->get('a'));
  }

  public function testCreateObjectWithMalformedJSONFileAsParameter()
  {
    $file = $this->fixturePath . 'json/broken.json';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }

  public function testCreateObjectWithEmptyJSONFileAsParameter()
  {
    $file = $this->fixturePath . 'json/empty.json';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }

  public function testCreateObjectWithNonexistentJSONFileAsParameter()
  {
    $file = $this->fixturePath . 'json/nonexistent.json';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }

  public function testCreateObjectWithPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/datatypes.php';
    $obj = new Radiergummi\Libconfig\Libconfig($file);
    
    $this->assertInstanceOf('Radiergummi\Libconfig\Libconfig', $obj);

    $this->assertEquals('test', $obj->get('a'));
  }

  public function testCreateObjectWithMalformedPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/broken.php';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }

  public function testCreateObjectWithEmptyPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/empty.php';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }
  
  public function testCreateObjectWithNonexistentPHPFileAsParameter()
  {
    $file = $this->fixturePath . 'php/nonexistent.php';

    $this->setExpectedException('\RuntimeException');

    $obj = new Radiergummi\Libconfig\Libconfig($file);
  }

  public function testCreateObjectWithFolderAsParameter()
  {
    $file = $this->fixturePath . 'json/subfolder/';
    $obj = new Radiergummi\Libconfig\Libconfig($file);

    $this->assertInstanceOf('Radiergummi\Libconfig\Libconfig', $obj);

    $this->assertEquals('example glossary', $obj->get('glossary.title'));
  }
}
