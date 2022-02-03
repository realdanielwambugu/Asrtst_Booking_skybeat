<?php

Namespace providers;

use providers\core\ServiceProvider;

use support\proxies\Config;


class DatabaseServiceProvider extends ServiceProvider
{
  /**
  * set database configuration path.
  * register bindings with the service container.
  *
  * @return void
  */
  public function register()
  {

    $this->app->singleton(\interfaces\ConnectionInterface::class, function ($app)
    {
        return new \database\Connection($app);
    });

     $this->app->singleton(\interfaces\QueryInterface::class, function ($app)
     {
          return new \database\builders\query\Builder();
     });

     $this->app->bind('schema', \database\builders\schema\Builder::class);

     $this->app->singleton('migrator', function ($app)
     {
         return new \database\migrations\core\Migrator($app);
     });

  }

  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
      if (mb_strtoupper(Config::get('migrations')) === 'ON')
      {
         $migrator = $this->app->make('migrator');

         $migrator->migrate();
      }

  }

}
