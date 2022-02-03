<?php

Namespace database\builders\schema;

use interfaces\QueryInterface;

use interfaces\ConnectionInterface;

class Builder
{
    private $builder;

    private $schema;

    private $tables = [];

    private $table;

    private $uniquesColumns = [];

    private $fields = [];

    private $columns = [];

    private $activeColumn;

    public function __construct(QueryInterface $builder, ConnectionInterface $connection)
    {
        $conn = $connection->start();

        $this->builder = $builder;

        $this->schema  = $connection->database();

        $this->create($conn, $this->schema);

        $connection = $connection->get();

        $builder->setConnection($connection);

    }

    public function query($sql)
    {
       return $this->builder->query($sql);
    }

    public function create($conn, $schema)
    {
       return $conn->query("CREATE SCHEMA IF NOT EXISTS {$schema}");
    }

    public function tableNames()
    {
        if (count($this->tables))
        {
            return $this->tables;
        }

        $tables = $this->query("SHOW TABLES")->fetchAll();

        if (is_array($tables) && !empty($tables))
        {
            foreach ($tables as  $table)
            {
               $this->tables[] =  reset($table);
            }
        }

        return $this->tables;
    }

    public function hasTable($table)
    {
       return in_array($table, $this->tableNames());
    }

    public function table($table, callable $columns)
    {
       $this->table = mb_strtolower($table);

       return $columns($this);
    }

    public function columnFields($table)
    {
        $columns =  $this->builder->getColumnFields($this->table);

        foreach ($columns as  $column)
        {
             if ($column['Key'] === 'UNI')
             {
                 $this->uniquesColumns[$column['Field']] = '';
             }

             $this->fields[$this->table][] = $column['Field'];
        }

        return $this->fields[$this->table];
    }

    public function hasColumn($table, $column)
    {
      if (isset($this->fields[$this->table]))
      {
          if (in_array($column, $this->fields[$this->table]))
          {
               return true;
          }
      }

      return in_array($column, $this->columnFields($table));
    }

    public function column($name)
    {
       $this->columns[$this->table][$name] = [$name];

       $this->activeColumn = $name;

       return $this;
    }

    public function constraint($constraint)
    {
       $this->columns[$this->table][$this->activeColumn][] = $constraint;

       return $this;
    }

    public function type($value)
    {
       return $this->constraint($value);
    }

    public function default($value, $sql = false)
    {
        $value = !$sql ? "'{$value}'" : $value;

        return $this->constraint("DEFAULT {$value}");
    }

    public function timestamp($column)
    {
        $this->column($column);

        return $this->constraint('TIMESTAMP');
    }

    public function nullable($bool = true)
    {
       $value = ($bool) ? 'NULL' : "NOT NULL";

       return $this->constraint($value);
    }

    public function isNotNullable($column)
    {
        if (in_array("AUTO_INCREMENT", $this->columns[$this->table][$column]))
        {
           return false;
        }

        if (in_array("UNIQUE", $this->columns[$this->table][$column]))
        {
           return false;
        }

        if (in_array("NULL", $this->columns[$this->table][$column]))
        {
           return false;
        }

        return true;
    }

    public function modifier($modifier)
    {
       return $this->constraint($modifier);
    }

    public function integer($column, $type = 'INT', $length = 11)
    {
        $this->column($column);

        return $this->type("{$type}({$length})");
    }

    public function bigInteger($column)
    {
       return $this->integer($column, 'BIGINT');
    }

    public function mediumInteger($column)
    {
       return $this->integer($column, 'MEDIUMINT');
    }

    public function smallInteger($column)
    {
      return $this->integer($column, 'SMALLINT');
    }

    public function tinyInteger($column)
    {
       return $this->integer($column, 'TINYINT');
    }

    public function boolean($column)
    {
       $this->column($column);

       return $this->type('BOOLEAN');
    }

    public function date($column, $type = 'DATE')
    {
       $this->column($column);

       return $this->type($type);
    }

    public function dateTime($column)
    {
       return $this->date($column, 'DATETIME');
    }

    public function time($column)
    {
       return $this->date($column, 'TIME');
    }

    public function year($column)
    {
       return $this->date($column, 'YEAR');
    }

    public function text($column, $type = 'TEXT')
    {
       $this->column($column);

       return $this->type($type);
    }

    public function char($column, $length = 255)
    {
       return $this->text($column, "CHAR({$length})");
    }

    public function varchar($column, $length = 255)
    {
       return $this->text($column, "VARCHAR({$length})");
    }

    public function tinyText($column)
    {
       return $this->text($column, 'TINYTEXT');
    }

    public function mediumText($column)
    {
       return $this->text($column, 'MEDIUMTEXT');
    }

    public function longText($column)
    {
       return $this->text($column, 'LONGTEXT');
    }

    public function blob($column, $type = 'BLOB')
    {
       $this->column($column);

       return $this->type($type);
    }

    public function tinyBlob($column)
    {
       return $this->blob($column, 'TINYBLOB');
    }

    public function mediumBlob($column)
    {
       return $this->blob($column, 'MEDIUMBLOB');
    }

    public function longBlob($column)
    {
       return $this->blob($column, 'LONGBLOB');
    }

    public function unsigned()
    {
      return $this->modifier('UNSIGNED');
    }

    public function primary()
    {
      return $this->modifier('PRIMARY KEY');
    }

    public function unique($column = null)
    {
        if (!$this->hasUniqueConstraint($column))
        {
            return $this->modifier('UNIQUE', $column)->required();
        }

        return $this;
    }

    public function hasUniqueConstraint($column = null)
    {
        if ($this->hasTable($this->table))
        {
           $this->hasColumn($this->table, $column);

           $column = !$column ? $this->activeColumn : $column;
        }

        return array_key_exists($column, $this->uniquesColumns);
    }

   public function autoIncrement()
   {
      return $this->modifier('AUTO_INCREMENT');
   }

   public function after($column)
   {
       return $this->modifier("AFTER {$column}");
   }

   public function first()
   {
       return $this->modifier("FIRST");
   }


   public function timestamps()
   {
      $this->swichSqlMode();

      $this->timestamp('created_at')->default("CURRENT_TIMESTAMP", true);

      $this->timestamp('updated_at')
            ->default("CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP", true)->nullable();

   }

   public function swichSqlMode($mode = 'STRICT_TRANS_TABLES')
   {
     return $this->query("SET SESSION sql_mode = {$mode}");
   }

   public function required()
   {
      return $this->nullable(false);
   }

   public function id($column = 'id')
   {
       return $this->bigInteger($column)->unsigned()->autoIncrement()->primary();
   }

   public function foreignId($column)
   {
       return $this->bigInteger($column)->unsigned();
   }

   public function string($column, $length = 255)
   {
       return $this->varchar($column, $length);
   }

  public function prepareColumns($modfy = false)
  {
     $columns = '';

     $x = 1;

     foreach ($this->columns[$this->table] as $column)
     {;
         if ($this->isNotNullable($column[0]))
         {
             array_splice($column, 2, 0, ['NOT NULL']);
         }

         if ($modfy)
         {
              $this->add($column[0], $column[1]);

              $columns .= $this->modify() . implode($column, ' ');
         }
         else
         {
              $columns .= implode($column, ' ');
         }

         if ($x < count($this->columns[$this->table]))
         {
             $columns .= ',';
         }

         $x++;
     }

     return $columns;
  }

  public function build()
  {
      $columns = $this->prepareColumns();

      if (!$this->hasTable($this->table))
      {
          $this->query("CREATE TABLE {$this->table} ({$columns})");
      }

      // $this->columns[$this->table] = [];

      return $this;
  }

  public function resetColumns()
  {
      if ($this->hasTable($this->table))
      {
         $this->columns[$this->table] = [];
      }

  }


  public function alter($table = null)
  {
      return " ALTER TABLE {$table}";
  }


  public function drop($table = null)
  {
      if (is_null($table))
      {
         return $this->dropColumn($this->activeColumn);
      }

     if ($this->hasTable($table))
     {
         $this->query("DROP TABLE {$table}");
     }

     return $this;
  }

  public function hasPrimary($table = null)
  {
    $table = is_null($table) ? $this->table : $table;

    return $this->query("SHOW INDEXES FROM {$this->table} WHERE Key_name = 'PRIMARY'")->rowCount();
  }

  public function dropPrimary($column = 'id')
  {
     if ($this->hasPrimary($this->table))
     {
         $sql = $this->alter($this->table) . " DROP PRIMARY KEY";

         $this->id($column)->rename('id');

         $this->query($sql);

     }
  }

   public function dropUnique($index = null)
   {
     $index = is_null($index) ? $this->activeColumn : $index;

     return $this->dropIndex($index);
  }

  public function dropIndex($index)
  {
      $sql = $this->alter($this->table) . " DROP index IF EXISTS {$index}";

      $this->query($sql);

      return $this;
  }

  public function dropColumn($column)
  {
       if ($this->hasColumn($this->table, $column))
       {
           $sql = $this->alter($this->table) . " DROP COLUMN {$column}";

           $this->query($sql);

           if (array_key_exists($column, $this->columns[$this->table]))
           {
               unset($this->columns[$this->table][$column]);
           }
       }
  }

  public function rename($from, $to = null)
  {
      if (is_null($to))
      {
         $to = $from;

         return $this->renameColumn($to);
      }

     $sql = $this->alter($from) . " RENAME TO {$to}";

     if ($this->hasTable($from) && !$this->hasTable($to))
     {
         $this->query($sql);

         $this->columns[$to] = $this->columns[$this->table];

         unset($this->columns[$this->table]);

         unset($this->tables[$this->table]);

         $this->tables[] = $to;

         $this->table = $to;
     }

     return $this;
  }

    public function modify($column = null)
    {
       return " MODIFY COLUMN {$column}";
    }


    public function add($column = null, $type)
    {
       if (!$this->hasColumn($this->table, $column))
       {
           $sql = $this->alter($this->table) . " ADD {$column} {$type}";

           $this->query($sql);
       }

      return $this;
    }

    public function renameColumn($to)
    {
        if ($this->hasTable($this->table))
        {

           if ($this->hasColumn($this->table, $this->activeColumn))
           {
               $type = $this->columns[$this->table][$this->activeColumn][1];

               $sql = " CHANGE COLUMN {$this->activeColumn} {$to} {$type}";

               $sql = $this->alter($this->table) . $sql;

               $this->query($sql);

               $this->fields[] = $to;

           }

           $this->columns[$this->table][$this->activeColumn][0] = $to;

           $this->columns[$this->table][$to] = $this->columns[$this->table][$this->activeColumn];

           unset($this->columns[$this->table][$this->activeColumn]);

           $this->activeColumn = $to;
        }

        return $this;
    }



  public function change($sql = null)
  {
     if ($this->hasTable($this->table))
     {
         if (count($this->columns[$this->table]))
         {
            $sql =  $this->prepareColumns(true);
         }

         $sql = $this->alter($this->table) . $sql;

         if ($this->hasPrimary($this->table))
         {
             $sql = str_replace('PRIMARY KEY', '', $sql);
         }

         $this->query($sql);
     }

     return $this;

  }



}
