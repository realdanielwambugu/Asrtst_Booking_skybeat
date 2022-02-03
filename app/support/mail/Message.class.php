<?php

Namespace support\Mail;

use interfaces\MailerInterface;


class Message
{

  protected $mailer;

  public function __construct(MailerInterface $mailer)
  {
      $this->mailer = $mailer;
  }

  public function from($address, $title = null)
  {
      $this->mailer->setFrom($address, $title);
  }

  public function to($address, $name = null)
  {
      $this->mailer->addAddress($address, $name);
  }

  public function subject($subject)
  {
      $this->mailer->Subject = $subject;
  }

  public function body($body)
  {
      $this->mailer->Body = $body;
  }

}
