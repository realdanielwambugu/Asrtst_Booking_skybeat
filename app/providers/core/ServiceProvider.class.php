<?php

Namespace providers\core;

use interfaces\serviceProviderInterface;

use exceptions\RuntimeException;

abstract class ServiceProvider implements serviceProviderInterface
{
  /**
  * Aplicatin instance.
  *
  * @var object
  */
  protected $app;

  /**
  * set the application instance.
  *
  * @param object app\base\Application
  * @return void;
  */
  public function __construct($app)
  {
      $this->app = $app;
  }


  /**
  * register bindings with the service container.
  *
  * @return object
  */
  public function register()
  {
      $className = get_called_class();

      throw new RuntimeException("Class {$className} should implement 'register' method ");

  }


}
