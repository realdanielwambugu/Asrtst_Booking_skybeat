<?php

Namespace database\migrations\core;

use support\proxies\Schema;

 abstract class Migration
{

  protected $direction;


  public function getDirection()
  {
      return $this->direction;
  }

  public function schema()
  {
      Schema::build();

      Schema::change();
  }

}
