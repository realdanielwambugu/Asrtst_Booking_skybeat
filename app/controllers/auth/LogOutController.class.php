<?php

Namespace controllers\auth;

use controllers\auth\AuthenticationController;

use support\proxies\Auth;

use support\proxies\Middleware;


class LogOutController extends AuthenticationController
{

  /**
  * Name of the configured model under protection
  *
  *@var string
  */
  protected $protect = 'user';

  /**
  * Kill user session
  *
  * @return string
  */
  public function logOut()
  {
      return $this->destroySession();
  }

  /**
  * redirect to if authentication is successful.
  *
  * @return string
  */
  public function redirect()
  {
     return 'templates\customer\Auth\login';
  }

}
