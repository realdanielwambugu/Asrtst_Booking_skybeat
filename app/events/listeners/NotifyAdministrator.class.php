<?php

namespace events\listeners;

use  events\UserRegisteredEvent;

class NotifyAdministrator
{
  /**
   * Create the event listener.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  /**
   * Handle the event.
   *
   * @param  object events\UserRegisteredEvent  $event
   * @return void
   */
  public function handle(UserRegisteredEvent $event)
  {
        echo "Hello admin new user has registered";
  }

}
