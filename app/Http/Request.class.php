<?php

Namespace Http;

use support\proxies\Config;

use exceptions\RuntimeException;

class Request
{
  /**
   * The controller class that should handle the request
   *
   * @var string
  */
   protected $controller;

  /**
   * The method to be invoked in the controller
   *
   * @var string
  */
   protected $method;

  /**
   *
   * @return string
  */
  public function __get($property)
  {
     if (property_exists($this, $property))
     {
         return $this->$property;
     }

     throw new RuntimeException("Undifined {$property} in Request::class");
  }

  /**
   * Get only requested properties
   *
   * @param mixed $property
   * @return array
  */
  public function only($property)
  {
     $properties = func_get_args();

     $found = [];

     foreach ($properties as $property)
     {
         $found[$property] = $this->$property;

         if (count($properties) < 2 && is_array($this->$property))
         {
             $found = $this->$property;
         }

     }

     return $found;
  }

  /**
   * romove some properties from request
   *
   * @param mixed $property
   * @return array
  */
  public function except($property)
  {
      $properties = func_get_args();

      $found = [];

      foreach ($this as $property => $value)
      {
          if (!in_array($property, $properties))
          { 
              $found[$property] = $this->$property;
          }
      }

      return $found;
  }

  /**
   * Extract the data in the request array
   *
   * @return object
   */
  public function set()
  {
     $request = $this->getRequest();

     if ($this->isValidRequest($request))
     {
         $this->controller = $this->controllersPath() . $request[0];

         $this->method = $request[1];

         unset($request[0], $request[1]);

         $this->setRequestParams($request);

         return $this;
     }

     throw new RuntimeException("Invalid http request");
  }


  public function __unset($property)
  {
       $properties = func_get_args();

       foreach ($properties as $property)
       {
           unset($this->$property);
       }

  }

  /**
   * set the request params as properties
   *
   * @param array $request
   * @return object $this
  */
  protected function setRequestParams(array $request)
  {
     foreach ($request as $property => $value)
     {
         $value = is_string($value) ? trim($value) : $value;

         $this->$property = $value;
     }

     return $this;
  }


  /**
  * Get the available request
  *
  * @return array
  */
  public function getRequest()
  {
     $request = array_merge($this->postRequest(), $this->getFilesRequest());

     if (!count($request))
     {
         $request = $this->defaultRequest();
     }

     return $request;
  }

  /**
   * Get default request from config\application.config
   *
   * @param array $request
   * @return mixed
   */
  public function defaultRequest($request = [])
  {
      return count($request) ? $request : Config::get('default.request');
  }

  /**
  * receive and set the http post request.
  *
  * @return mixed
  */
  protected function postRequest()
  {
     return isset($_POST) ? $_POST : [];
  }

  /**
  * receive and set the http files request.
  *
  * @return mixed
  */
  protected function getFilesRequest()
  {
     return isset($_FILES) ? $_FILES : [];
  }

  /**
  * Check if the request contains a controller and method
  *
  * @param array $request
  * @return bool
  */
  protected function isValidRequest(array $request)
  {
      return isset($request[0]) && isset($request[1]);
  }

  /**
  * Get the default controllers path from config\application.config
  *
  * @return string
  */
  public function controllersPath()
  {
     return Config::get('default.paths.controllersPath');
  }


}
