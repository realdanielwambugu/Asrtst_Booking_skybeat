<?php

Namespace exceptions;

use Exception;

/**
 *
 */
class Unauthorized extends Exception
{

  public function __construct($message)
  {
      dnd($message);
  }

}
