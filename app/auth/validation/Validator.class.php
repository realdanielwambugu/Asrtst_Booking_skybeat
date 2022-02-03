<?php

Namespace auth\validation;

use auth\validation\ErrorHandler;

use support\proxies\Config;

use support\proxies\Storage;

class Validator
{

  protected $errorHandler;

  protected $verifier;

  protected $constraints = ['required', 'min', 'max', 'email', 'alpha', 'match', 'unique', 'verify', 'file'];

  protected $items;

  protected $messages = [];

  public function __construct(Verifier $verifier, ErrorHandler $errorHandler)
  {
     $this->verifier = $verifier;

     $this->errorHandler = $errorHandler;
  }

  public function check($items, $constraints = 'default', $messages = null)
  {
      $items = is_object($items) ? (array) $items : $items;

      $constraints = is_array($constraints) ? $constraints : $this->constraints($constraints);

      $this->messages = $messages;

      if (!is_array($messages))
      {
         if (is_null($messages))
         {
             $this->messages = $this->messages($constraints['messages']);
         }
         else
         {
             $this->messages = $this->messages($messages);
         }

      }

      $this->items = $items;

      foreach ($items as $item => $value)
      {
         if (in_array($item, array_keys($constraints)))
         {
             $this->validate([
               'field' => $item,
               'value' => $value,
               'constraints' => $constraints[$item],
             ]);
         }
      }

      return $this;
  }

  public function constraints($key)
  {
     return Config::get("validation.constraints.{$key}");
  }

  public function messages($key)
  {
     return Config::get("validation.messages.{$key}");
  }

  public function fails()
  {
      return $this->errorHandler->hasErrors();
  }

  public function errors()
  {
      return $this->errorHandler;
  }

  protected function validate($item)
  {
     $field = $item['field'];

     $constraints = $item['constraints'];

     if (!is_array($item['constraints']))
     {
         $constraints = $this->buildconstraintsArray($item['constraints']);
     }

      foreach ($constraints as $constraint => $satisfer)
      {
          if (in_array($constraint, $this->constraints))
          {
              $params = [$field, $item['value'], $satisfer];

              if (!call_user_func_array([$this, $constraint], $params))
              {
                   $this->errorHandler->setError(
                        str_replace(
                          [':field', ':satisfer'],

                          [ucfirst($field), $satisfer],

                          $this->messages[$constraint]), $field
                   );
              }
          }
      }

  }

  public function buildconstraintsArray($constraints, $newconstraints = null)
  {
      $constraints = explode( '|', $constraints);

       foreach ($constraints as $Stringconstraint)
       {
          if (contains($Stringconstraint, ':'))
          {
              $constraint = cutString($Stringconstraint, ':', 'both');

              $newconstraints[$constraint['start']] = $constraint['end'];
          }
          else
          {
              $newconstraints[$Stringconstraint] = true;
          }

       }

       return $newconstraints;
  }

  protected function required($field, $value, $satisfer)
  {
      return !empty(trim($value));
  }

  protected function min($field, $value, $satisfer)
  {
     return mb_strlen($value) >= $satisfer;
  }

  protected function max($field, $value, $satisfer)
  {
     if (is_array($value))
     {
          return $this->file($field, $value, $satisfer, 'size');
     }

     return mb_strlen($value) <= $satisfer;
  }

  protected function email($field, $value, $satisfer)
  {
     return filter_var($value, FILTER_VALIDATE_EMAIL);
  }

  protected function alpha($field, $value, $satisfer)
  {
     return ctype_alnum($value);
  }

  protected function match($field, $value, $satisfer)
  {
     return $value === $this->items[$satisfer];
  }

  protected function unique($field, $value, $satisfer)
  {
      return  $this->verifier->existsInModel($satisfer, $field, $value) < 1 ;
  }

  public function verify($field, $value, $satisfer)
  {
     return !$this->unique($field, $value, $satisfer);
  }

  public function file($field, $value, $satisfer, $check = 'type')
  {
     $value = is_multi_array($value) ? $value : [$value];

     $files = Storage::check($value);

     foreach ($files as $file)
     {
         if ($check === 'type')
         {
             $response = in_array(cutString($file->type, '/', false), explode(',', $satisfer));
         }
         else
         {
            $response = $file->size <= $satisfer;
         }
     }

     return $response;
  }


}
