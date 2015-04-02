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
    $this->fixturePath = dirname(__FILE__) . '/fixtures/main/';
  }

 public function testObjectCreated() 
  {
      $obj = new Radiergummi\Libconfig\Config();
      $this->assertInstanceOf('Radiergummi\Libconfig\Config', $obj);
  }
}
