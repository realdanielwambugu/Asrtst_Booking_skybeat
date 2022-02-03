<?php

Namespace database\migrations;

use database\migrations\core\Migration;

use support\proxies\Schema;

use proxies\query;

class ArtistTable extends Migration
{

 protected $direction = 'up';

 public function up()
 {

   Schema::table('artist', function ($table)
   {
       $table->id();

       $table->string('photo')->default('default.jpg');

       $table->string('name');

       $table->integer('category_id');

       $table->string('country');

       $table->date('birth_day');

       $table->string('cost_per_hr');

       $table->text('biography');

       $table->timestamps();
   });

 }

 public function down()
 {
     Schema::drop('user');
 }
}
