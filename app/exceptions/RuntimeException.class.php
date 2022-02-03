<?php

Namespace exceptions;

use Exception;

/**
 *
 */
class RuntimeException extends Exception
{

  public function __construct($message)
  {
      $error = "
      Error Type: Undifined
      File : {$this->getFile()}
      Line : {$this->getLine()}
      Error Message: {$message}";

       dnd($error);
  }

}
