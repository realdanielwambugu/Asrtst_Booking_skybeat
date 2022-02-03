<?php

Namespace support\proxies\core;

use exceptions\RuntimeException;


class proxy
{
  /**
  * resolved class instances.
  *
  * @var array
  */
  protected static $resolvedAccessors = [];

  protected static $singleton = true;

  /**
  * receive the method requested by the static proxy together with it araguments.
  * if accessor class is resolved use the resolved instance.
  * Else resolve it and mark it as resolved.
  * invoke the requested method using the acquired instance and pass the args.
  *
  * @param string $accessor
  * @param array $args
  * @return mixed
  */
  public static function __callStatic($method,$args)
  {
     $accessor = static::getAccessor();

     if (static::accessorIsResolved($accessor) && static::$singleton != false)
     {
         $instance = static::getAccessorInstance($accessor);
     }
     else
     {
         $instance = static::resolveAccessor($accessor);
         static::setAccessorAsResolved($accessor,$instance);
     }

     return static::callAccessorMethod($instance, $method, $args);
  }


  /**
  * Create an instance of the accessor class.
  *
  * @param string $accessor
  * @return object
  */
  public function resolveAccessor($accessor)
  {
     return app()->make($accessor);
  }


  /**
  * Mark accessor class as resolved.
  *
  * @param string $accessor
  * @param string $instance
  * @return void
  */
  public function setAccessorAsResolved($accessor,$instance)
  {
     static::$resolvedAccessors[$accessor] = $instance;
  }

  /**
  * get resolved accessor class instance.
  *
  * @param string $accessor
  * @return object
  */
  public function getAccessorInstance($accessor)
  {
     return static::$resolvedAccessors[$accessor];
  }

  public function instance($accessor = null)
  {
      $accessor = is_null($accessor) ? static::getAccessor() : $accessor;

      if (static::accessorIsResolved($accessor))
      {
          return static::getAccessorInstance($accessor);
      }

      $instance = static::resolveAccessor($accessor);

      static::setAccessorAsResolved($accessor,$instance);

      return $instance;
  }

  /**
  * invoke the requested accessor class method.
  *
  * @param string $instance
  * @param string $method
  * @param array $args
  * @return mixed
  */
  public static function callAccessorMethod($instance,$method,$args)
  {
     return call_user_func_array([$instance, $method], $args);
  }

  /**
  * check if accessor class is resolved.
  *
  * @param string $accessor
  * @return bool
  */
  public static function accessorIsResolved($accessor)
  {
     return array_key_exists($accessor, static::$resolvedAccessors);
  }

  /**
  * throw an Exception if the child class does not override getAccessor method.
  *
  * @return mixed
  */
  public static function getAccessor()
  {
     throw new RuntimeException('Proxy does not implement getAccessor method.');
  }

  public function resolvedAccessors()
  {
      static::$resolvedAccessors = [];
  }
}
