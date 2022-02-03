<?php

Namespace models;

use models\core\Model;

use database\Connection;


class Category extends Model
{

  public function artist()
  {
     return $this->hasMany('models\Artist');
  }

}
