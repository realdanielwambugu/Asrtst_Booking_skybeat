<?php

Namespace database\migrations\core;

 class Migrator
{

  private $migrations;

  private $app;

  public function __construct($app)
  {
     $this->app = $app;
  }

  public function getMigarations()
  {
     return $this->migrations = glob('database/migrations/*.php');
  }

  public function migrate()
  {
      $migrations = $this->getMigarations();

      foreach ($migrations as $migration)
      {
         $migration = $this->resolveMigration($migration);

         if (mb_strtolower($migration->getDirection()) === 'down')
         {
             $migration->down();
         }
         else
         {
             $migration->up();
         }

         $migration->schema();
      }

      return $this;
  }

  public function resolveMigration($migration)
  {
    $migration = "database\migrations\\" .basename($migration, '.class.php');

    return $this->app->make($migration);
  }

}
