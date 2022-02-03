<?php

Namespace auth\access\state;

use interfaces\SessionInterface;

class Session implements SessionInterface
{

  private $started;

  public function __construct()
  {
      $this->start();
  }

  public function isStarted()
  {
      return $this->started;
  }

  public function start()
  {
      if (!$this->isStarted())
      {
           session_start();

           $this->started = true;
      }

      return $this;
  }

  public function has($key)
  {
     return isset($_SESSION[$key]);
  }

  public function all()
  {
     return $_SESSION;
  }


  public function set($key, $value)
  {
       $_SESSION[$key] = $value;

       return $this;
  }

  public function push($into, $value)
  {
     $data = $this->resolveValueAndKey($into);

     if ($this->has($data['key']));
     {
        $session = $this->get($data['key']);

        $session[$data['value']] = $value;

        $this->set($data['key'], $session);

     }
  }

  public function get($key)
  {
    if($this->has($key))
    {
       return  $_SESSION[$key];
    }

    return null;
  }

  public function pull($session)
  {
     $data = $this->resolveValueAndKey($session);

     if (array_key_exists($data['value'], $key = $this->get($data['key'])))
     {
         return $key[$data['value']];
     }

     return false;
  }

  public function resolveValueAndKey($session)
  {
     $key   = cutString($session, '.');

     $value = cutString($session, '.', false);

     return ['key' => $key, 'value' => $value];
  }

  public function destroy($key)
  {
    unset($_SESSION[$key]);

    return $this;
  }


}
