<?php

Namespace support\Mail;

use interfaces\MailerInterface;

use exceptions\MailNotSentException;

use exceptions\MissingFileException;

use support\proxies\Config;


class Mailer
{

  protected $mailer;

  public function __construct(MailerInterface $mailer)
  {
      $this->mailer = $mailer;
  }


  public function send($template, $data, $callback)
  {
      $message = new Message($this->mailer);

      $template = $this->prepareTemplate($template, $data);

      $message->body($template);

      call_user_func($callback, $message);

      if(!$this->mailer->send())
      {
         throw new MailNotSentException($this->mailer->ErrorInfo);
      }
  }

  public function prepareTemplate($template, $data)
  {
      if (!$this->hasTemplate($template))
      {
        $template = $this->getTemplate($template);
      }

      extract($data);

      ob_start();

      require_once $template;

      $template = ob_get_clean();

      ob_end_clean();

      return $template;
  }


  public function hasTemplate($template)
  {
      return file_exists($template);
  }


  public function getTemplate($basename)
  {
      $template = Config::get('default.paths.mailTemplatePath') . $basename . '.php';

      if (!$this->hasTemplate($template))
      {
        throw new MissingFileException("Email template {$template} not found");

      }

      return $template;
  }


}
