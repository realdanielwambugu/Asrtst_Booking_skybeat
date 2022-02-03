<?php

Namespace exceptions;

use Exception;

/**
 *
 */
class MailNotSentException extends Exception
{
    public function __construct($error)
    {
        dnd("oops Email Not sent: ERROR  {$error}");
    }
}
