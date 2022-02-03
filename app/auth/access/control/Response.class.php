<?php

Namespace auth\access\control;


class Response
{

  private $status;

  private $message;

  public function set($status, $message)
  {
     $this->status = $status;

     $this->message = $message;
  }

   public function allow($message = null)
   {
       $this->set(true, $message);

       return $this;
   }

   public function deny($message = null)
   {
       $this->set(false, $message);

       return $this;
   }

  public function allowed()
  {
     return $this->status;
  }

  public function denied()
  {
     return $this->status;
  }

  public function message()
  {
     return $this->message;
  }

}
