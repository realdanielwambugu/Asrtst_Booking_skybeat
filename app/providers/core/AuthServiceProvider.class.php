<?php

Namespace providers\core;

abstract class AuthServiceProvider extends ServiceProvider
{

  /**
  * policies to be rgistered with the gate.
  *
  * @var array
  */
   protected $policies = [];


  /**
  * Bind policies to the service container
  * This makes policies easly accessed by the Gate class through the container
  *
  * @return void;
  */
  public function registerPolicies()
  {
     $policies = $this->policies;

     $this->app->bind('policies', function ($app) use ($policies)
     {
         return $policies;
     });

  }


  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
      $this->registerPolicies();
  }
}
