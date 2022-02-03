<?php

Namespace database\migrations;

use database\migrations\core\Migration;

use support\proxies\Schema;

use proxies\query;

class UserTable extends Migration
{

 protected $direction = 'up';

 public function up()
 {

   Schema::table('user', function ($table)
   {
       $table->id();

       $table->string('photo')->default("default.jpg");

       $table->string('fullName');

       $table->string('email')->unique();

       $table->integer('phone')->unique();

       $table->text('password');

       $table->string('status')->nullable();

       $table->integer('code')->nullable();

       $table->timestamps();
   });

 }

 public function down()
 {
     Schema::drop('user');
 }
}
