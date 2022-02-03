<?php

Namespace auth\validation;

use auth\validation\ErrorHandler;

class Verifier
{


  public function existsInModel($model, $field, $value)
  {
     $model = 'models\\' . ucfirst($model);

     return call_user_func_array([new $model, 'wherein'], [$field, [$value]])->count($field);

  }


}
