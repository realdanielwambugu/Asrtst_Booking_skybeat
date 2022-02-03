<?php
  /**
  * Php Web development Skeleton.
  *
  * @package Xcholars.
  * @author   Daniel N Wambugu <pairplanet@gmail.com>.
  *
  */

  /*
  |--------------------------------------------------------------------------
  |  Require in  Utility Functions.
  |--------------------------------------------------------------------------
  |
  | Including utility functions on this file makes them available for use on
  | every part of the application.
  */

    require_once 'support/utilities.functions.php';

  /*
  |--------------------------------------------------------------------------
  | Require in The Class Auto Loader file.
  |--------------------------------------------------------------------------
  |
  | Standard PHP Library comes with a built-in funtion (spl_autoload_register())
  | This funtion allows classes to be autloaded automatically.
  | Require in the file where we have utilized this function.
  | For this to work ensure you end all class files with (.class.php) extension.
  */

    require_once 'config/ClassAutoload.config.php';

  /*
  |--------------------------------------------------------------------------
  |  Set Application Configuration
  |--------------------------------------------------------------------------
  |
  | Set the  config to make them availble in every class
  | through the Config proxy get method.
  */

   support\proxies\Config::set('config/*.php');

  /*
  |--------------------------------------------------------------------------
  |  Bootstrap The Application
  |--------------------------------------------------------------------------
  |
  | Create a new instance of the application class. instanitiate both
  | Dispatcher and request class.
  |
  */

    $app = new base\Application;


    $dispatcher = $app->make(Http\Dispatcher::class);


    $dispatcher->dispatch(new Http\Request);
