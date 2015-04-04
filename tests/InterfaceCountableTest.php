<?php

  public function testCountableInterfaceSucceeds()
    {
        $obj = new Configula\Config($this->configPath);
        $this->assertEquals(4, count($obj));
    }
