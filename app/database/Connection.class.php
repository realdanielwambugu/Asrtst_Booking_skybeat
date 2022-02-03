<?php

Namespace database;

use interfaces\ConnectionInterface;

use base\Application;

use database\builders\query\Builder;

use database\builders\schema\Builder as Schema;

use support\proxies\Config;

use exceptions\MissingConfigException;

use PDO;

class Connection implements ConnectionInterface
{
  /**
  * Appllication instance.
  *
  * @var array
  */
  private $app;

  /**
  * database configuration.
  *
  * @var array
  */
  private $config = [];

  /**
  * database pdo  instance.
  *
  * @var object
  */
  private $pdo;

  /**
  * active database
  *
  * @var array
  */
  private $database;

   public function __construct(Application $app)
   {
       $this->app = $app;
   }

  /**
  * set database configuration.
  *
  * @param object $config
  * @return object
  */
  public function Configure($config = null)
  {
     $config = Config::get("connections.{$config}");

     try
     {
         if (!$config)
         {
             throw new MissingConfigException("default database config not set");
         }

         return $this->config = is_array($config) ? (object) $config : $config;

     } catch (MissingConfigException $e)
     {
         die($e->getMessage());
     }
  }

  /**
  * Establish database connection.
  *
  * @return object
  */
  public function connect()
  {
      $dsn = 'mysql:host='.$this->config->host;

      $pdo = new PDO($dsn,$this->config->username,$this->config->password);

      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $pdo;
  }

  /**
  * get database connection by config name.
  *
  * @param string $connection
  * @return object
  */
  public function start($connectionName = null, $database = null)
  {
     $connectionName = is_null($connectionName) ? 'default' : $connectionName;

     if (!$this->pdo)
     {
         $this->Configure($connectionName);

         $connection = $this->connect();

         $this->pdo = $connection;
     }

     $database = is_null($database) ? $this->config->database : $database;

     $this->database = $database;

     return $this->pdo;
  }

  /**
  * retun pdo object
  *
  * @return object
  */
  public function get($database = null)
  {
     $this->useDatabase($database);

     return $this->pdo;
  }

  /**
  * set database to be used.
  *
  */
  public function database()
  {
     return $this->database;
  }

  /**
  * select database to be used.
  *
  */
  public function useDatabase($database = null)
  {
     $database = is_null($database) ? $this->database : $database;

     return $this->pdo->query("USE {$database}");
  }

  /**
  * close database connection by name.
  *
  * @param string $connection
  * @return void
  */
  public function close($connectionName)
  {
      $this->connection = null;
  }

}
