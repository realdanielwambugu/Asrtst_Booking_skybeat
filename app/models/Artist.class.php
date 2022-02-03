<?php

Namespace models;

use models\core\Model;

use database\Connection;


class Artist extends Model
{

  public function song()
  {
     return $this->hasMany('models\Songs');
  }

  public function booking()
  {
     return $this->hasMany('models\Booking');
  }

  public function category()
  {
     return $this->belongsTo('models\Category');
  }


}
