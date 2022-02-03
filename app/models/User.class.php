<?php

  Namespace models;

  use models\core\Model;

  use database\Connection;

  use support\proxies\Gate;



  class User extends Model
  {

     public function bookings()
     {
         return $this->hasMany('models\Booking');
     }

     public function can($ability, $modelClass = null)
     {
         return Gate::forUser($this)->allows($ability, $modelClass);
     }

     public function cant($ability, $modelClass = null)
     {
         return !$this->can($ability, $modelClass);
     }

     public function isAdmin()
     {
       return $this->status == 1;
     }


          public function isBlocked()
          {
            return $this->status == 'blocked';
          }


     public function number()
     {
        return $this->count();
     }

  }

























// $orders = $this->set('order_id',$this->id)
//                ->set('user_id',3)
//                ->find('orders');
 // $users = $this->find('user');
 //
  // $users2 = $this->prepare('SELECT * From user')->execute()->fetch();
 //
 //




//    foreach ($users as $key => $value) {
// echo $value->fullName.'<br>';
// var_dump($key);
//    }

 // $user_id = $this->set('fullName',$this->name)
 //                  ->set('password',$this->password)
 //                  ->set('email','daniel@gmail.com')
 //                  ->create('user')
 //                  ->getInsertId();
 //  echo $user_id;

 // $this->set('order_id',78)
 //      ->set('product_id',7)
 //      ->update('orders',['user_id' => 3]);
 //

 // $this->set('user_id',3)->delete('orders');





//
// echo '<pre>'.print_r($users2,true).'</pre>';

 // $result = $this->action('UPDATE')
                 // ->fields(['product_id' => 44444,'order_id' => 1,])
                 // ->table('orders')
                 // ->values(['product_id' => 789,'user_id' => 100])
                 // ->where(['user_id' => 3])
                 // ->orderBy('order_id')
                 // ->direction('DESC')
                 // ->limit(3)
                 // ->prepare()
                 // ->execute();
                 // ->fetch();
  // echo '<pre>'.print_r($orders,true).'</pre>';
