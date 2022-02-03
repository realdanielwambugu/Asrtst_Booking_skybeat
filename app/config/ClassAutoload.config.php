<?php
/**
* Autoload classes/interfaces with spl_autoload_register function
* Replace (\) with (/) in $item
* check if $item is a class and include it.
* else check if $item is a interface  and include it.
* if $item is neither return error;
*
* @param  $item
* @return void
*/
 spl_autoload_register(function($item)
 {
     $item      = strtr($item, "\\", '/');
     $class     = $item.".class.php";
     $interface = $item.".php";

     if (file_exists($class))
     {
         require_once $class;

     }else if(file_exists($interface))
     {
         require_once $interface;

     }else
     {
         //handle missing file error
     }
 });
