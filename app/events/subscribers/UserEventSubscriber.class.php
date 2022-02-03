<?php

namespace events\subscribers;

use events\Core\Event;


class UserEventSubscriber
{
  /**
   * Handle user login events.
   */
  public function handleUserLogin($event)
  {
     echo $event->user->email;
  }

  /**
  * Handle user logout events.
  */
  public function handleUserLogout($event)
  {
     echo "user logout";
  }

  /**
  * Register the listeners for the subscriber.
  *
  * @param object \events\core\Dispatcher  $event
  */
  public function subscribe(Event $event)
  {
      // $event->listen('UserRegisteredEvent', 'UserEventSubscriber@handleUserLogin');

      // $event->listen('logoutEvent', 'UserEventSubscriber@handleUserLogout');
  }
}
