<?php

Namespace providers;

use providers\core\ServiceProvider;

use support\proxies\Config;


class MailServiceProvider extends ServiceProvider
{

  /**
  * register bindings with the service container.
  *
  * @return object
  */
  public function register()
  {
      $this->app->singleton(\interfaces\MailerInterface::class, function ($app)
      {
          return new \vendor\PHPMailer\src\PHPMailer;
      });
  }

  /**
  * Activities to be performed after bindings are registerd.
  *
  * @return void
  */
  public function boot()
  {
      $config = Config::get('mail');

      $mail = $this->app->make(\interfaces\MailerInterface::class);

      $mail->isSMTP($config['smtp']);

      $mail->SMTPDebug  = $config['smtp_debug'];

      $mail->Host       = $config['host'];

      $mail->Port       = $config['port'];

      $mail->SMTPSecure = $config['smtp_secure'];

      $mail->SMTPAuth   = $config['smtp_auth'];

      $mail->Username   = $config['username'];

      $mail->Password   = $config['password'];

      $mail->isHTML($config['html']);
  }
}
