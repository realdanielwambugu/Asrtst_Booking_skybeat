<?php

Namespace providers;

use providers\core\AuthServiceProvider as ServiceProvider;

use support\proxies\Gate;

use support\proxies\Response;

class AuthServiceProvider extends ServiceProvider
{


  /**
  * Model policies you want registerd with the Gate.
  *
  * @var array
  */
  protected $policies = [
       \models\Booking::class => \auth\access\Policies\BookingPolicy::class,
  ];


  /**
  * register bindings with the service container.
  *
  * @return void
  */
  public function register()
  {
     $this->app->singleton(\interfaces\SessionInterface::class, function ($app)
     {
         return new \auth\access\state\Session;
     });

     $this->app->bind(\auth\validation\ErrorHandler::class, function ($app)
     {
          return new \auth\validation\ErrorHandler;
     });

     $this->app->singleton(\auth\access\control\Response::class, function ($app)
     {
          return new \auth\access\control\Response;
     });

  }



  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
     Parent::boot();

     Gate::define('update_bookings', function ($user, $booking)
     {
         return $user->id === $booking->user_id;
     });


  }

}
