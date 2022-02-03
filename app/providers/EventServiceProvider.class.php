<?php

Namespace providers;

use providers\core\EventServiceProvider as ServiceProvider;

use support\proxies\Event;


class EventServiceProvider extends ServiceProvider
{

 /**
  * Mappings of events and their Listeners.
  *
  * @var array
  */
  protected $listen = [
     UserRegisteredEvent::class => [
         SendUserWelcomeEmail::class,
         NotifyAdministrator::class,
     ],

     ConfirmResetPasswordEvent::class => [
       SendPasswordResetCode::class,
     ]
  ];

 /**
  * List of events subscribers.
  *
  * @var array
  */
  protected $subscribe = [
     UserEventSubscriber::class,
  ];

  /**
  * register bindings with the service container.
  *
  * @return object
  */
  public function register()
  {


  }


  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
     Parent::boot();

     // Event::listen(UserRegisteredEvent::class, function ($event)
     // {
     //
     // });


  }

}
