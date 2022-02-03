<?php

namespace events\listeners;

use events\UserRegisteredEvent;

use support\proxies\Mail;

class SendUserWelcomeEmail
{
  /**
   * Handle the event.
   *
   * @param  object events\UserRegisteredEvent  $event
   * @return void
   */
  public function handle(UserRegisteredEvent $event)
  {
      Mail::send('WelcomeEmail', (array) $event->user, function ($mail) use($event)
      {
          $mail->from('newspair@example.com', 'Welcome Email');

          $mail->to('pairplanet@gmail.com', $event->user->fullName);

          $mail->subject('Weilcome Here');
      });

  }

}
