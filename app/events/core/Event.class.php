<?php

Namespace events\core;

use base\Application;

use exceptions\RuntimeException;

use support\proxies\Config;


class Event
{
  /**
  * Application instance.
  *
  * @var object
  */
  protected $app;

  /**
   * events Listeners Mappings.
   *
   * @var array
   */
   protected $listen = [];

  /**
  * set the application instance.
  *
  * @param object base\Application
  * @return void
  */
  public function __construct(Application $app)
  {
      $this->app = $app;
  }


  public function fire($event)
  {
     $eventClass = ClassBasename(get_class($event));

     if (array_key_exists($eventClass, $this->listen))
     {
         if (is_callable($this->listen[$eventClass]))
         {
             $this->resolveCallaback($this->listen[$eventClass], $event);
         }

     }

     $listeners = $this->app->make($eventClass);

     if ($listeners = $this->getListeners($eventClass))
     {
          return $this->resolveListeners($listeners, $event);
     }

     throw new RuntimeException("{$eventClass} is not registerd in providers\EventServiceProvider");

  }

  public function resolveCallaback(callable $callabck, $event)
  {
       return call_user_func($callabck, $event);
  }


  public function getListeners($eventClass)
  {
       $listeners = $this->app->make($eventClass);

       return is_array($listeners) ? $listeners : false;
  }

  public function resolveListeners($listeners, $event)
  {
      $path = $this->getListenersPath();

      foreach ($listeners as $listener)
      {
          $listener = $path . ClassBasename($listener);

          $listener = $this->app->make($listener);

          $listener->handle($event);
      }

      return $this;
  }


  public function getListenersPath()
  {
      return Config::get('default.paths.listenersPath');
  }


  public function getSubscribersPath()
  {
      return Config::get('default.paths.eventsSubscribersPath');
  }


  public function listen($event, $listener)
  {
      if (contains($listener, "@"))
      {
          $listener = $this->subscribe($event, $listener);
      }

      $this->listen[ClassBasename($event)] = $listener;

      return $this;
  }

  public function subscribe($event, $listener)
  {
      $result = CutString($listener, '@', 'both', ['subscriber', 'method']);

      $subscriber = $this->getSubscribersPath() .'\\'. $result['subscriber'];

      $method = $result['method'];

      $app = $this->app;

      return function ($event) use($subscriber, $method, $app)
      {
          return call_user_func([$app->make($subscriber), $method], $event);
      };
  }
}
