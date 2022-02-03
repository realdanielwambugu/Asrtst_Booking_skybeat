<?php

Namespace interfaces;

interface SessionInterface{


  public function isStarted();


  public function start();


  public function has($key);


  public function set($key, $value);


  public function push($into, $value);


  public function get($key);


  public function pull($session);


  public function resolveValueAndKey($session);


}
