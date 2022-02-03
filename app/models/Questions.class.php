<?php

Namespace models;

use models\core\Model;

use database\Connection;


class Questions extends Model
{

     public function user()
     {
       return $this->belongsTo('models\User');
     }
}
