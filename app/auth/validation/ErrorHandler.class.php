<?php

Namespace auth\validation;


class ErrorHandler
{
    protected $errors = [];


    public function setError($error, $key = null)
    {
        if ($key)
        {
            $this->errors[$key][] = $error;
        }
        else
        {
           $this->errors[] = $error;
        }

    }

    public function all($key = null)
    {
        return isset($this->errors[$key]) ? $this->errors[$key] : $this->errors;
    }

    public function first($key = null)
    {
      if (!$key && isset($this->errors[$key][0]))
      {
          return $this->errors[$key][0];
      }

        return  $this->firstOfAll();
    }

    public function firstOfAll()
    {
       foreach ($this->errors as $error)
       {
           return $error[0];
       }
    }


    public function hasErrors()
    {
        return count($this->all()) ? true : false;
    }



}
