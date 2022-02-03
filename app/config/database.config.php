<?php
return [
/*
 | Provide database config information.
 | Make sure you provide default configuration to avoid errors.
 |
*/
  'migrations' => 'OFF',

  'connections' => [

     'default' => [
       'driver'    => 'mysql',
       'host'      => 'localhost',
       'database'  => 'skyBeat',
       'username'  => 'root',
       'password'  => '',
       'charset'   => 'utf8',
       'collation' => 'utf8_unicode_ci',
       'prefix'    => '',
     ],

   ],
];
