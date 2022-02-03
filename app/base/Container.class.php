<?php

Namespace base;

use interfaces\ContainerInterface;

use exceptions\ClassNotInstantiable;

use ReflectionClass;

use ReflectionMethod;

use Closure;

use ArrayAccess;



class Container implements ArrayAccess, ContainerInterface
{

  /**
  * service bindings.
  *
  * @var array
  */
  protected $bindings = [];

  /**
  * singleton instances.
  *
  * @var array
  */
  protected $SingletonInstances = [];

  /**
  * container instance.
  *
  * @var array
  */
  protected static $instance;

  /**
  * set container instance.
  *
  * @param object app\interfaces\ContainerInterface
  *
  * @return object
  */
  public static function setInstance(ContainerInterface $container = null)
  {
      return static::$instance = $container;
  }

  /**
  * get container instance.
  *
  * @return object
  */
  public static function getInstance()
  {
    if (is_null(static::$instance))
    {
        static::$instance = new static;
    }

    return static::$instance;
  }

  /**
  * register bindings with the container.
  *
  * @param string $key
  * @param string $value
  * @param array $args
  * @param string $singleton
  *
  * @return void
  */
  public function bind($key, $value = null, $args = [], $singleton = false)
  {
      if (is_array($value))
      {
         $args = $value;

         $value = $key;
      }

      $this->bindings[$key] = compact('value', 'singleton', 'args');
  }

  /**
  * register singleton bindings with the container.
  *
  * @param string $key
  * @param string $value
  * @param array $args
  *
  * @return mixed
  */
  public function singleton($key, $value = null, $args = [])
  {
     return $this->bind($key, $value, $args, true);
  }

  /**
  * get registered bindings by key.
  *
  * @param string $key
  * @return string
  */
  protected function getBinding($key)
  {
      if (array_key_exists($key, $this->bindings))
      {
         return $this->bindings[$key];
      }

      return null;
  }


  /**
  * check weather a certain binding is marked as singleton.
  *
  * @param string $key
  * @return bool
  */
  protected function isSingleton($key)
  {
      $binding = $this->getBinding($key);

      if (!$binding)
      {
         return false;
      }

      return $binding['singleton'];
  }

  /**
  * check weather a singleton binding is aready resolved.
  *
  * @param string $key
  * @return bool
  */
  protected function SingletonResolved($key)
  {
     return array_key_exists($key, $this->SingletonInstances);
  }

  /**
  * get singleton instance from the resolved singletons array.
  *
  * @param string $key
  *
  * @return mixed
  */
  protected function getSingletonInstance($key)
  {
     if ($this->isSingleton($key) && $this->SingletonResolved($key))
     {
         return $this->SingletonInstances[$key];
     }

     return false;
  }

  /**
  * Get the bindings for the key from bindings array if it is registered.
  * marge the araguments passed as params with the class binded araguments.
  * Get the class name from the binded array using index 'value'.
  *
  * If the key has no registered bindings use the key as the className.
  *
  * If the class singleton instance exists return it.
  * Else build an  instance for the class.
  * Check if the class is singleton and register the instance if true.
  *
  * @param string $key
  * @param array $args
  *
  * @return object
  */
  public function make($key, $args = [])
  {
      $class = $this->getBinding($key);

      if (!$class)
      {
          $class = $key;
      }

      if (is_array($class))
      {
         $args = array_merge($args, $class['args']);

         $class = $class['value'];
      }

      if ($singleton = $this->getSingletonInstance($key))
      {
         return $singleton;
      }

      $object = $this->buildObject($class, $args);

      if ($this->isSingleton($key))
      {
         $this->registerSingleton($key, $object);
      }

      return $object;
  }

  /**
  * Register an instance as singleton.
  *
  * @param string $key
  * @param object $object
  *
  * @return void
  */
  protected function registerSingleton($key, $object)
  {
     $this->SingletonInstances[$key] = $object;
  }

  /**
  * Use reflection Api to get class type hinted dependencies from constructor
  * Check if the class has setDependencies method if no constructor is defuned
  * use ReflectionMethod to get type hinted dependencies in setDependencies
  *
  * @param string $class
  * @param array $args
  *
  * @return object
  */
  protected function buildObject($class, $args, $instance = null)
  {
      if (is_callabe($class))
      {
         return $class($this);
      }


      $reflector = new ReflectionClass($class);

      if(!$reflector->isInstantiable())
      {
          throw new ClassNotInstantiable("Class $class is not instantiable");
      }


      if ($constructor = $reflector->getConstructor())
      {
          $dependencies = $constructor->getParameters();

          $resolvedDependencies = $this->resolve($dependencies, $args);

          $instance = $reflector->newInstanceArgs($resolvedDependencies);
      }


      if ($reflector->hasMethod('setDependencies'))
      {
          $instance = is_null($instance) ? $reflector->newInstance() : $instance;

          $reflectionMethod = new ReflectionMethod($class,'setDependencies');

          $dependencies = $reflectionMethod->getParameters();

          $resolvedDependencies = $this->resolve($dependencies, $args);

          $reflectionMethod->invokeArgs($instance, $resolvedDependencies);
      }


      return is_null($instance) ? $reflector->newInstance() : $instance;
  }

  /**
  * resolve all dependencies for the class and it dependencies.
  *
  * @param array $dependencies
  * @param array $args
  *
  * @return array
  */
  protected function resolve($dependencies, $args = [])
  {
      foreach ($dependencies as $dependency)
      {
         if ($dependency->isOptional()) continue;

         if ($dependency->isArray()) continue;

         $class = $dependency->getClass();

         if ($class === null) continue;

         if (get_Class($this) === $class->name)
         {
             array_push($args, $this);

             continue;
         }

         array_push($args, $this->make($class->name));

      }

      return $args;
  }

  /**
  * resolve dependencies for the provides key and return  it instance.
  *
  * @param string $key
  *
  * @return object
  */
  public function offsetGet($key)
  {
     return $this->make($key);
  }

  /**
  * register bindings with the container
  *
  * @param string $dependencies
  * @param string $value
  *
  * @return void
  */
  public function offsetSet($key, $value)
  {
     $this->bind($key, $value);
  }

  /**
  * check if bindings exist in the container
  *
  * @param string $key
  *
  * @return bool
  */
  public function offsetExists($key)
  {
     return array_key_exists($key, $this->bindings);
  }

  /**
  * remove registered bindings from the container.
  *
  * @param string $dependencies
  *
  * @return void
  */
  public function offsetUnset($key)
  {
     unset($this->bindings[$key]);
  }
}
