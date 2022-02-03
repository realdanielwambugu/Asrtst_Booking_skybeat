<?php

Namespace models;

use models\core\Model;

use database\Connection;


class Songs extends Model
{

  public function artist()
  {
     return $this->belongsTo('models\Artist');
  }

}
