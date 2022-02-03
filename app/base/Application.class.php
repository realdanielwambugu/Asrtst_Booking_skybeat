<?php

Namespace base;

use support\proxies\Config;

class Application extends Container
{
  /**
  * all app providers.
  *
  * @var array
  */
  private $providers = [];

  /**
  * all registered providers instances.
  *
  * @var array
  */
  private $registeredProviders = [];

  /**
  * invoke base methods.
  *
  * @return void
  */
  public function __construct()
  {
      static::setInstance($this);

      $this->registerProviders();

      $this->bootProviders();
  }


  /**
  * return providers list in app\config\application.config.php
  *
  * @return string
  */
  public function getProviders()
  {
      return $this->providers = Config::get('providers');
  }

  /**
  * register all providers with the service container.
  *
  * @param string
  * @return void
  */
  public function registerProviders()
  {
      foreach ($this->getProviders() as $provider)
      {
         $provider = $this->resolveProvider($provider);

         $provider->register();

         $this->markProviderAsRegistered($provider);
      }

  }

  /**
  * create new instance of provider class
  *
  * @param string
  * @return object
  */
  public function resolveProvider($provider)
  {
     return new $provider($this);
  }

  /**
  * boot or rgisterd providers
  *
  * @return void
  */
  public function bootProviders()
  {
    foreach ($this->registeredProviders as $registeredProvider)
    {
        if ($this->providerIsBootable($registeredProvider))
        {
           $registeredProvider->boot();
        }
    }
  }

  /**
  * check if provider has the boot method
  *
  * @param object $provider
  * @return bool
  */
  public function providerIsBootable($provider)
  {
     return method_exists($provider, 'boot');
  }


  /**
  * Check if provider is registered
  *
  * @param string
  * @return bool
  */
  public function providerIsRegistered($provider)
  {
     return in_array($provider, $this->registeredProviders);
  }

  /**
  * add provider to registered providers
  *
  * @param object
  * @return bool
  */
  public function markProviderAsRegistered($provider)
  {
      $this->registeredProviders[] = $provider;
  }
}
