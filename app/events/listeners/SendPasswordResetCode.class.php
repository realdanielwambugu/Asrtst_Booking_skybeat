<?php

namespace events\listeners;

use events\ConfirmResetPasswordEvent;

use support\proxies\Mail;

class SendPasswordResetCode
{
  /**
   * Handle the event.
   *
   * @param  object events\UserRegisteredEvent  $event
   * @return void
   */
  public function handle(ConfirmResetPasswordEvent $event)
  {
      Mail::send('resetPasswordEmail', (array) $event->user, function ($mail) use($event)
      {
          $mail->from('skybeatCustmer@help.com', 'Rest Password');

          $mail->to($event->user->email);

          $mail->subject('Skybeat Reset Password Code');
      });

  }

}
