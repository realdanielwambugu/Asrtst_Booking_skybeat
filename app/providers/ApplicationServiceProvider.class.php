<?php

Namespace providers;

use providers\core\ServiceProvider;

/**
 *
 */
class ApplicationServiceProvider extends ServiceProvider
{

  /**
  * register bindings with the service container.
  *
  * @return object
  */
  public function register()
  {
      $this->app->singleton(\middleware\core\Middleware::class, function ($app)
      {
          return new \middleware\core\Middleware($app);
      });

      $this->app->bind('inflector', function ($app)
      {
           return \vendor\DoctrineInflector\InflectorFactory::create()->build();
      });
  }

}
