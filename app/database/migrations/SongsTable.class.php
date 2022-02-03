<?php

Namespace database\migrations;

use database\migrations\core\Migration;

use support\proxies\Schema;

use proxies\query;

class SongsTable extends Migration
{

 protected $direction = 'up';

 public function up()
 {

   Schema::table('songs', function ($table)
   {
       $table->id();

       $table->integer('artist_id');

       $table->string('title');

       $table->string('album');

       $table->year('releaseYear');

       $table->timestamps();
   });

 }

 public function down()
 {
     Schema::drop('user');
 }
}
