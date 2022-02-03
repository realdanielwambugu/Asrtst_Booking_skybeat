<?php

Namespace exceptions;

use Exception;

/**
 *
 */
class InvalidArgument extends Exception
{

  public function __construct($message)
  {
      $error = "
      Error Type: Invalid Argument
      Line : {$this->getLine()}
      File : {$this->getFile()}
      Error Message: {$message}";

       dnd($error);
  }

}
