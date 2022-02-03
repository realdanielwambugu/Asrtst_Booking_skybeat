<?php

Namespace database\migrations;

use database\migrations\core\Migration;

use support\proxies\Schema;

use proxies\query;

class ArtistCategoryTable extends Migration
{

 protected $direction = 'up';

 public function up()
 {

   Schema::table('category', function ($table)
   {
       $table->id();

       $table->string('category');

       $table->timestamps();
   });

 }

 public function down()
 {
     Schema::drop('user');
 }
}
