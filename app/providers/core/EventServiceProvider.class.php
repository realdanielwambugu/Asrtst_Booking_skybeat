<?php

Namespace providers\core;

use support\proxies\Config;

use support\proxies\Event;


abstract class EventServiceProvider extends ServiceProvider
{
  /**
   * Mappings of events and their Listeners.
   *
   * @var array
   */
   protected $listen = [

   ];

  /**
  * List of events subscribers.
  *
  * @var array
  */
   protected $subscribe = [

   ];


  /**
  * Bind event listeners to the container.
  * This makes eventsListeners easly accessed by the core Event class through the container
  *
  * @return void;
  */
  public function registerEventListners()
  {
      $events = $this->listen;

      foreach ($events as $event => $listeners)
      {
         $this->app->bind(ClassBasename($event), function ($app) use ($listeners)
         {
             return $listeners;
         });
      }

  }

  /**
  * Bind event subscribers to the container.
  * This makes policies easly accessed by the core Event class through the container
  *
  * @return void;
  */
  public function registerEventsSubscribers()
  {
      $subscribers = $this->subscribe;

      $path = Config::get('default.paths.eventsSubscribersPath');

      foreach ($subscribers as $subscriber)
      {
          $subscriber = $path .'\\'. ClassBasename($subscriber);

          $this->app->make($subscriber)->subscribe(Event::instance());
      }

  }


  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
      $this->registerEventListners();

      $this->registerEventsSubscribers();
  }


}
