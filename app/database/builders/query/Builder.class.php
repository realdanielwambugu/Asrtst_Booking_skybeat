<?php

Namespace database\builders\query;

use interfaces\ConnectionInterface;

use interfaces\QueryInterface;

use PDO;

use RecursiveIteratorIterator;

use RecursiveArrayIterator;

class Builder implements QueryInterface
{
  /**
  * database connection pdo object.
  *
  * @var object base\database\Connection
  */
  private $pdo;

  /**
  * The table which the query is targeting.
  *
  * @var string
  */
  private $table;

  /**
  * Where Conditions indicating which rows the query should target.
  *
  * @var array
  */
  private $columns = [];

  /**
  * column field names
  *
  * @var array
  */
  private $fields = [];

  /**
  * Values for $conditionalColumns.
  *
  * @var array
  */
  private $values = [];

  /**
  * sql query statement.
  *
  * @var string
  */
  private $sql = [];

  /**
  * The prepared statement.
  *
  * @var string
  */
  private $stmt;

  /**
  * pdo fall back fetch mode
  *
  */
  private $fetchMode = PDO::FETCH_OBJ;

  /**
  * @return object
  */
  public function setConnection($connection)
  {
      $this->pdo = $connection;

      return $this;
  }

  /**
  * @return object
  */
  public function getConnection()
  {
      return $this->pdo;
  }

  /**
  * set the table to query
  *
  * @param object $table
  * @return object $this
  */
  public function table($table)
  {
     $this->table = $table;

     return $this;
  }

  public function getColumnFields($table)
  {
      return $this->pdo->query("SHOW COLUMNS FROM {$table}")->fetchAll();
  }

  /**
  * prepare select sql statement
  *
  * @param object \models\$object
  * @return object \models\$object
  */
  public function select()
  {

     $columns = func_get_args();

     if (is_multi_array($columns))
     {
       $array = new RecursiveIteratorIterator
       (
            new RecursiveArrayIterator($columns)
       );

       $columns = iterator_to_array($array,false);
     }

     $columns = count($columns) ? implode($columns, ',') : "*";

     $sql = "SELECT {$columns} FROM {$this->table}" . implode($this->sql, '');

     $result = $this->query($sql);

     $this->reset();

     return $result;
  }

  /**
  *
  *
  * @param array $data
  * @return integer lastInsertId
  */
  public function insert(array $data)
  {
     $columns = implode(array_keys($data),',');

     $this->values = array_values($data);

     $placeholders = $this->generatePlaceholders($this->values, ',', false);

     $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

     $this->query($sql);

     $this->reset();

     return $this->pdo->lastInsertId();
  }

  /**
  *
  *
  * @param array $data
  * @return bool
  */
  public function update(array $data)
  {
     $columns = array_keys($data);

     $this->values = array_merge(array_values($data), $this->values);

     array_walk($columns, function(&$column)
     {
         $column .= ' = ';
     });

     $placeholders = $this->generatePlaceholders($columns, ',', true);

     $sql = "UPDATE {$this->table} SET {$placeholders}";

     $sql = $sql . implode($this->sql, ',');

     $result = $this->query($sql) ? $this->rowCount() : false;

     $this->reset();

     return $result;
  }

  /**
  *
  *
  * @param array $conditions
  * @return object $this
  */
  public function delete()
  {
     $sql = "DELETE FROM {$this->table}";

     $sql = $sql . implode($this->sql, '');

     $result = $this->query($sql) ? $this->rowCount() : false;

     $this->reset();

     return $result;
  }

  /**
  *
  *
  * @param string $column
  * @param string $direction
  * @return object $this
  */
   public function orderBy($column, $direction)
   {
       $this->sql[] = " ORDER BY {$column} {$direction}";

       return $this;
   }

   /**
   *
   *
   * @param string $column
   * @param string $direction
   * @return object $this
   */
    public function limit($number)
    {
        $this->sql[] = " LIMIT {$number}";

        return $this;
    }

  /**
  *
  *
  * @param array $conditions
  * @return object $this
  */
  public function where()
  {
     $conditions = func_get_args();

     $conditions =  $this->toMultiArray($conditions);

     $columns = [];

     foreach ($conditions as $condition)
     {
         if (is_array($condition))
         {
             $columns[] = $condition[0] . $condition[1];

             $this->values[] = $condition[2];
         }
     }

     $operator = 'WHERE';

     $seplator = 'AND';

     if (in_array('OR', $conditions))
     {
         $operator = 'OR';

         $seplator = 'OR';
     }

     $operator = in_array('NOT', $conditions) ? $operator . ' NOT' : $operator;

     $placeholders = $this->generatePlaceholders($columns, $seplator, true);

     $this->sql[]  = " {$operator} {$placeholders}";

     return $this;
  }

  /**
  *
  *
  * @param array $conditions
  * @return object $this
  */
  public function orWhere()
  {
     $conditions = func_get_args();

     $conditions = $this->toMultiArray($conditions);

     $conditions[] = 'OR';

     call_user_func_array([$this, 'where'], $conditions);

     return $this;
  }

  public function toMultiArray(array $array)
  {
     return is_multi_array($array) ? $array : [$array];
  }

  /**
  *
  *
  * @param array $conditions
  * @return object $this
  */
  public function whereNot()
  {
     $conditions = func_get_args();

     $conditions = $this->toMultiArray($conditions);

     $conditions[] = 'NOT';

     call_user_func_array([$this, 'where'], $conditions);

     return $this;
  }

  /**
  *
  * @return void $this
  */

  public function setConditionalSql()
  {
     $args = func_get_args();

     $brackets = false;

     if (array_key_exists(5, $args))
     {
         $brackets = ($args[5]) ? true : false;
     }

     $compare  = array_key_exists(6, $args) ? true : false;

     $this->values = array_merge($this->values, $args[1]);

     $placeholders = $this->generatePlaceholders($args[1], $args[3], $compare);

     $placeholders = ($brackets) ? "({$placeholders})" : $placeholders;

     $this->sql[] =' '.$args[2].' '.$args[0].' '.$args[4].' '.$placeholders;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @param string $operator
  * @return object $this
  */
  public function whereIn($column, array $values, $operator = 'WHERE')
  {
     $this->setConditionalSql($column, $values, $operator, ',', 'IN', true);

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function orWhereIn($column, array $values)
  {
     $this->whereIn($column, $values, 'OR');

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function andWhereIn($column, array $values)
  {
     $operator = count($this->sql) === 0 ? 'WHERE' : 'AND';

     $this->whereIn($column, $values, $operator);

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function whereNotIn($column, array $values, $operator = 'WHERE')
  {
     $this->setConditionalSql($column, $values, $operator, ',', 'NOT IN', true);

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function andWhereNotIn($column, array $values)
  {
     $this->whereNotIn($column, $values, 'AND');

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function whereBetween($column, array $values, $operator = 'WHERE')
  {
     $this->setConditionalSql($column, $values, $operator, 'AND', 'BETWEEN');

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function orWhereBetween($column, array $values)
  {
     $this->whereBetween($column, $values, 'OR');

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function whereNotBetween($column, array $values, $operator = 'WHERE')
  {
     $this->setConditionalSql($column, $values, $operator, 'AND', 'NOT BETWEEN');

     return $this;
  }

  /**
  *
  * @param string $column
  * @param array $values
  * @return object $this
  */
  public function andWhereNotBetween($column, array $values)
  {
     $this->whereNotBetween($column, $values, 'AND');

     return $this;
  }


  /**
  *
  *
  * @param array $columns
  * @param string $separator
  * @param bool $compare
  * @return string
  */
  public function generatePlaceholders(array $columns, $separator, $compare = false)
  {
      $placeholders = null;

      $x = 1;

      foreach ($columns as $column)
      {
         $placeholders .= ($compare) ? $column .'?' : '?';

         if ($x < count($columns))
         {
             $placeholders .= " {$separator} ";
         }

         $x++;
      }

      return $placeholders;
  }

  /**
  * fetch single row into the model object
  *
  * @param object \models\$object
  * @return object \models\$object
  */
  public function fetch($object = null, $stmt = null)
  {
      $object = is_null($object) ? (object) [] : $object;

      if ($stmt)
      {
          $this->stmt = $stmt;
      }

      $this->stmt->setFetchMode(PDO::FETCH_INTO, $object);

      if (is_null($object))
      {
         $this->stmt->setFetchMode($this->fetchMode);
      }

      $this->stmt->fetch();


      if ($this->rowCount() < 1 && !is_null($object))
      {
          $object = $this->fetchColumnFields($object);
      }

      return $object;
  }


  /**
  * fetch all rows into the model object
  *
  * @param object \models\$object
  * @param object $stmt
  * @return object \models\$object
  */
  public function fetchAll($object = null, $stmt = null)
  {
      $class = !is_null($object) ? get_class($object) : '';

      if ($stmt)
      {
          $this->stmt = $stmt;
      }

      $this->stmt->setFetchMode(PDO::FETCH_CLASS, $class);

      if (is_null($object))
      {
         $this->stmt->setFetchMode($this->fetchMode);
      }

      $result = $this->stmt->fetchAll();

      $Object = is_null($object) ? $object : [$this->fetchColumnFields($object)];

      return count($result) ? $result : $Object;
  }


  public function fetchColumnFields($object)
  {
     if ($object)
     {
         foreach ($this->getColumnFields($this->table) as $fields)
         {
             $field = $fields['Field'];

             $object->$field = '';

         }
     }

     return $object;
  }

  /**
  * prepare the query
  *
  * @param object $sql
  * @return object $this
  */
  public function query($sql = null)
  { 
     if (!$sql)
     {
        $sql = $this->sql;
     }

     $this->prepare($sql)->bindValues($this->values)->execute();

     return $this;
  }

  /**
  * set prepared statement
  *
  * @param object $sql
  * @return object $this
  */
  public function prepare($sql = null)
  {
    if (!$sql)
    {
       $sql = $this->sql;
    }

    $this->stmt = $this->pdo->prepare($sql);

    return $this;
  }

  /**
  * bind value to prepared statement
  *
  * @param array $valuesMultiArray
  * @return object $this
  */
  public function bindValues(array $values)
  {
      $x = 1;

      foreach ($values as $value)
      {
          $this->stmt->bindValue($x,$value);

          $x++;
      }

    return $this;
  }

  /**
  * Execute thw query
  *
  * @param array $stmt
  * @return object $this
  */
  public function execute($stmt = null)
  {
     if ($stmt)
     {
         $this->stmt = $stmt;
     }

     $this->stmt->execute();

     return $this;
  }

  /**
  *
  * @return integer
  */
  public function rowCount()
  {
     return ($this->stmt) ? $this->stmt->rowCount() : null;
  }

  /**
  *
  * @return void
  */
  public function reset()
  {
    $this->values = [];

    $this->sql = [];
  }


}
