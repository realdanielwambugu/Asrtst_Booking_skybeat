<?php

Namespace models\core;

use support\proxies\Connect;

use support\proxies\Query;

use queryException;


abstract class Model
{
  /**
   * database pdo instance
   *
   * @var string
   */
  protected $conn;

  /**
   * table associeted with the modal.
   *
   * @var string
   */
  protected $query;

  /**
   * table associeted with the modal.
   *
   * @var string
   */
  protected $table;

  /**
   *
   * @var string
   */
  protected $relationships;

  /**
   *
   * @var string
   */
  protected $properties = [];

  /**
   * table primaryKey.
   *
   * @var string
   */
   protected $primaryKey = 'id';

   /**
    *
    * @var bool
    */
    protected $active = false;

  /**
  * Set database connection.
  *
  * @param object $database.
  * @return void.
  */
  public function __construct()
  {
     $this->connection();

     $this->table = $this->setTable();
  }


  public function connection($database = null)
  {

    $this->conn = Connect::start();

    Connect::useDatabase($database);

    Query::setConnection($this->conn);

    $this->query = Query::instance();
  }

  /**
  * Set table/modal
  *
  * @return string
  */
  private function setTable()
  {
     return $this->getModalName($this, 'lowerCase');
  }

  public function getModalName($model, $case = null)
  {
     $model = new \ReflectionClass($model);

     $model = $model->getShortName();

     return ($case === 'lowerCase') ? mb_strtolower($model) : $model;
  }

    /**
  * Dynamically set an property.
  *
  * @param string $key
  * @return object
  */
  public function __set($property, $value)
  {
     if ($this->isRelationshipProperty($property))
     {
         return $this->relationships()->$property = $value;
     }

     $this->$property = $value;

     $this->properties[]  =  $property;

     return $this;
  }

  /**
  * Dynamically get an property.
  *
  * @param string $property
  * @return mixed
  */
  public function __get($property)
  {
     try
     {
         if (property_exists($this, $property))
         {
             return $this->$property;
         }

         if ($this->isRelationshipProperty($property))
         {
            return $this->relationships()->$property;
         }

         if ($this->isChildMethodReference($property))
         {
             if ($relation = $this->$property()->relation)
             {
                 return $relation;
             }
         }

         throw new \Exception(
             "Property {$property} does not exist in {$this->table} model.");

     } catch (\Exception $e)
     {
         die("ERROR: ".$e->getMessage());
     }

  }

  public function isChildMethodReference($string)
  {
     return method_exists($this, $string);
  }


  /**
  *
  * @return object models/core/ModelRelations
  */
  public function relationships()
  {
    if ($this->relationships)
    {
      return $this->relationships;
    }

    return  $this->relationships = new ModelRelations($this);
  }

  public function isRelationshipProperty($property)
  {
     return property_exists($this->relationships(), $property);
  }

  public function isRelationshipMethod($method)
  {
     return method_exists($this->relationships(), $method);
  }

  public function getRelationship($method, $args)
  {
     if ($this->isRelationshipMethod($method))
     {
         return call_user_func_array([$this->relationships(), $method], $args);
     }

     return false;
  }

  public function isQuery($method)
  {
       return method_exists($this->query, $method);;
  }

  public function invokeQueryMethod($method, $args)
  {
     if ($this->isQuery($method))
     {
          return call_user_func_array([$this->query, $method], $args);
     }

     return false;
  }

  public function get($args = [])
  {
     $this->query->table($this->table);

     return $this->query->select($args)->fetchAll($this);
  }

  public function first($args = [])
  {
     return $this->take(1)->get($args)[0];
  }

  /**
  *
  * @param @method $method
  * @param array $method
  * @return array
  */
  public function __call($method, $args)
  {
      if ($relation = $this->getRelationship($method, $args))
      {
         return $relation;
      }

      if ($this->invokeQueryMethod($method, $args))
      {
          return $this;
      }
  }

  /**
  *
  * @param array $args
  * @return mixed
  */
  public function create($data)
  {
     $data = !is_array($data) ? (array) $data : $data;

     $this->query->table($this->table);

     if ($this->id = $this->query->insert($data))
     {
        foreach ($data as $column => $value)
        {
           $this->$column = $value;
        }

        return $this;
     }

     return false;  //error when inserting
  }

  /**
  *
  * @return integer
  */
  public function getPrimaryKey()
  {
    $key = $this->primaryKey;

    return $this->$key;
  }

  /**
  *
  * @param array $args
  * @return object
  */
  public function update($data)
  {
      $data = !is_array($data) ? (array) $data : $data;

      $id = $this->getPrimaryKey();

      return $this->query->table($this->table)
                         ->where($this->primaryKey, '=', $id)
                         ->update($data);
  }

  /**
  *
  * @return object
  */
  public function save()
  {
      $data = [];

      foreach ($this->properties as $property)
      {
          if ($property != 'primaryKey' && $property != 'id')
          {
               $data[$property] = $this->$property;
          }
      }

      return ($this->active) ? $this->update($data) : $this->create($data);
  }

  /**
  *
  * @param array $args
  * @return object
  */
  public function find()
  {
     $args = func_get_args();

     if (count($args) != 1)
     {
         $result = $this->query->table($this->table)
                               ->whereIn($this->primaryKey, $args)
                               ->select()->fetchAll($this);

         return $result;
      }

       $this->query->table($this->table)
                   ->where($this->primaryKey, '=', $args[0])
                   ->select()->fetch($this);

       $this->active = true;

       return $this;
  }

  /**
  *
  * @return array
  */
  public function all()
  {
     $args = func_get_args();

     return $this->get($args);
  }

  public function sortBy($column, $direction)
  {
      $this->query->orderBy($column, $direction);

      return $this;
  }

  public function take($number)
  {
      $this->query->limit($number);

      return $this;
  }

  /**
  *
  * @return integer
  */
  public function delete()
  {
     $id = $this->getPrimaryKey();

     return $this->query->table($this->table)
                        ->where($this->primaryKey, '=', $id)
                        ->delete();
  }

  public function count($args = [])
  {
     $this->get($args);

     return $this->query->rowCount();

  }

  /**
  *
  * @return integer
  */
  public function destroy()
  {
     $keys = func_get_args();

     return $this->query->table($this->table)
                        ->whereIn($this->primaryKey, $keys)
                        ->delete();
  }


}
