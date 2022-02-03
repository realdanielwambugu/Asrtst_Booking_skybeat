<?php

Namespace Http;


class Response
{

  private $message;

  public function message($message)
  {
     $this->message = $message;

     return $this;
  }

  public function send()
  {
      if (is_string($this->message))
      {
         echo $this->message;
      }
      else
      {
         d($this->message);
      }
  }
}
