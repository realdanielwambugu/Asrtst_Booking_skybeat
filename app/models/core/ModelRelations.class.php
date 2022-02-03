<?php

Namespace models\core;

class ModelRelations
{

  private $model;

  private $relativeModel;

  private $localKey = 'id';

  private $foreignKey;

  private $localKeyValue;

  private $relation;

  private $eagerLoad;


  public function __construct($model)
  {
     $this->model = $model;
  }

  public function __set($property, $value)
  {
       return $this->$property = $value;
  }

  public function __get($property)
  {
     try
     {
         if (property_exists($this, $property))
         {
             return $this->$property;
         }

         throw new \Exception(
         "Property {$property} does not exist in ModelRelations class.");

     } catch (\Exception $e)
     {
         die("ERROR: ".$e->getMessage());
     }
  }


  public function getForeignKey($model)
  {
     $modelName = $this->model->getModalName($model, 'lowerCase');

     return $modelName . "_" . $this->localKey;
  }


  public function getLocalKeyValue()
  {
    $localKey = $this->localKey;

    return $this->model->$localKey;
  }


  public function hasOne($model, $foreignKey = null)
  {
     if (is_null($foreignKey))
     {
       $foreignKey = $this->getForeignKey($this->model);
     }

     if ($data = $this->getEagerLoad($model, $foreignKey))
     {
        return $data;
     }

     $value = $this->getLocalKeyValue();

     $instance = app()->make($model);

     $instance->primaryKey = $foreignKey;

     $this->relation = $instance->find($value);

    return $this;
  }


  public function belongsTo($model, $foreignKey = null)
  {
    $foreignKey = is_null($foreignKey) ? $this->getForeignKey($model): $foreignKey;

    if ($data = $this->getEagerLoad($model, $foreignKey))
    {
       return $data;
    }

    $value = $this->model->$foreignKey;

    $this->relation = app()->make($model)->find($value);

    return $this;
  }


  public function hasMany($model, $foreignKey = null)
  {
     if (is_null($foreignKey))
     {
         $this->foreignKey = $this->getForeignKey($this->model);
     }

     if ($data = $this->getEagerLoad($model, $foreignKey))
     {
        return $data;
     }

     $this->localKeyValue = $this->getLocalKeyValue();

     $this->relativeModel = app()->make($model);

     $this->relation = $this->relativeModel
                            ->where($this->foreignKey, '=', $this->localKeyValue)
                            ->get();

     return $this;
  }

  public function getEagerLoad($model, $foreignKey)
  {
      if ($this->eagerLoad === true)
      {
         return ['model'=> $model, 'foreignKey' => $foreignKey];
      }

      return false;
  }

  public function getForeignKeyCondition()
  {
    return $this->relativeModel->andWhereIn($this->foreignKey, [$this->localKeyValue]);
  }

  public function chainForeignKeyCondition($method)
  {
     $chainedCondition = false;

     if (property_exists($this, 'foreignKeyConditionChained'))
     {
         $chainedCondition = $this->foreignKeyConditionChained;
     }

     if ($method === 'sortBy')
     {
         $this->getForeignKeyCondition();

         $this->foreignKeyConditionChained = true;
     }

     if ($method === 'take')
     {
         if (!$chainedCondition)
         {
            $this->getForeignKeyCondition();
         }
     }

  }

  public function get($args = [])
  {
      return $this->relativeModel->get($args);
  }

  public function first($args = [])
  {
      return $this->relativeModel->first($args);
  }

  public function __call($method, $args)
  {
     if ($this->relativeModel->isQuery($method))
     {
         call_user_func_array([$this->relativeModel->query, $method], $args);

         return $this;
     }

     if (method_exists($this->relativeModel, $method))
     {
         $this->chainForeignKeyCondition($method);

         call_user_func_array([$this->relativeModel, $method], $args);

         return $this;
     }

  }


  public function with($relationships, $model = null)
  {
     $relationships = is_array($relationships) ? $relationships : [$relationships];

     $requesterModel = is_null($model) ? $this->model : $model;

     return $this->loadRelationship($requesterModel, $relationships);
  }


  public function loadRelationship($requesterModel, $relationships)
  {
     $models = $requesterModel->all();

     $requesterModel->eagerLoad = true;

     foreach ($relationships as $relationship)
     {
         $nestedRelationship = $relationship;

         $relationship = cutString($relationship, '.');

         $data = call_user_func([$requesterModel, $relationship], null);

         $values = $this->getForeignKeyValues($models, $data['foreignKey']);

         $relations = $this->getRelationship($data['model'], $values, $this->localKey);

        if (strpos($nestedRelationship, '.'))
        {
           $nestedRelationship = cutString($nestedRelationship, '.', false);

           $relations = $this->with($nestedRelationship, $relations[0]);
        }

         $name = $relationship;

         $models = $this->pushRelationship($models, $relations, $this->localKey, $data['foreignKey'],$name);
     }


    return $models;
  }


  public function getForeignKeyValues($models, $foreignKey)
  {
     $values = [];

     foreach ($models as $model)
     {
         $values[] = $model->$foreignKey;
     }

     return $values;
  }


  public function getRelationship($relativeModel, $values, $localKey)
  {
     $this->relativeModel = app()->make($relativeModel);

     $relations = $this->relativeModel->whereIn($localKey, $values)->get();

     return $relations;
  }


  public function pushRelationship($models, $relations, $localKey, $foreignKey, $name)
  {
      foreach ($models as $model)
      {
          foreach ($relations as $relation)
          {
              if ($model->$foreignKey == $relation->$localKey)
              {
                   $model->$name = $relation;
              }
          }
      }

     return $models;
  }

  public function delete()
  {
     $this->relation = $this->relativeModel->query
                            ->where($this->foreignKey, '=', $this->localKeyValue)
                            ->delete();
     return $this;
  }



}
