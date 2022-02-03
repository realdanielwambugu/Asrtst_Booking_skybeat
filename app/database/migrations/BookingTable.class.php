<?php

Namespace database\migrations;

use database\migrations\core\Migration;

use support\proxies\Schema;

use proxies\query;

class BookingTable extends Migration
{

 protected $direction = 'up';

 public function up()
 {

   Schema::table('booking', function ($table)
   {
       $table->id();

       $table->integer('user_id');

       $table->integer('artist_id');

       $table->string('cost');

       $table->string('mpesa_code')->rename('payment_code');

       $table->date('book_date');

       $table->time('from_time');

       $table->time('to_time');

       $table->string('status')->default('pending');

       $table->timestamps();
   });

 }

 public function down()
 {
     Schema::drop('user');
 }
}
