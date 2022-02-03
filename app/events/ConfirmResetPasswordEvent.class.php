<?php

namespace events;

use models\User;


class ConfirmResetPasswordEvent
{
  /**
  * user instance.
  *
  * @var object
  */
  public $user;

  /**
  * Create a new event instance.
  *
  * @param  object models\User  $user
  * @return void
  */
  public function __construct(User $user)
  {
        $this->user = $user;
  }

}
