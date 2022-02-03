<?php

Namespace support;



class Configuration
{
  /**
  * config config.
  *
  * @var array
  */
  protected $config = [];

  /**
  * set the config config.
  *
  * @param array $file
  * @return void
  */
  public function set($files)
  {
     foreach (glob($files) as $config)
     {
         if (is_array($config = require_once $config))
         {
             $this->config[] =  $config;
         }
     }
  }

  /**
  * check if config key is set and pull the config.
  *
  * @param string $key
  * @return mixed
  */
  public function get($key)
  {
     $segments = explode('.', $key);

     $requestedConfig = null;

     foreach ($this->config as $config)
     {
         if (array_key_exists(cutString($key, '.'), $config))
         {
             $requestedConfig = $config;
         }

     }

    return $this->getSegment($requestedConfig, $segments);

  }


  /**
  * chunck the config array to make it accessible as parts.
  *
  * @param array $config
  * @param array $segments
  * @return mixed
  */
  public function getSegment($config, $segments)
  {
      foreach ($segments as $segment)
      {
          $config = isset($config[$segment]) ? $config[$segment] : $config;
      }

      return $config;
  }



}
