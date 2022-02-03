<?php

Namespace controllers\auth;

use controllers\auth\AuthenticationController;

use support\proxies\Auth;

use support\proxies\Middleware;

use support\proxies\Session;

class LoginController extends AuthenticationController
{

  /**
  * Name of the configured model under protection
  *
  *@var string
  */
  protected $protect = 'user';

  /**
  * assign middlewares
  *
  * @return void
  */
  public function middleware()
  {
      Middleware::assign('auth');
  }


  /**
  * Attempt authentication
  *
  * @return string
  */
  public function verify($credentials)
  {
      return $this->attempt($credentials);
  }

  /**
  * redirect to if authentication is successful.
  *
  * @return string
  */
  public function redirect()
  {
      if (Auth::user()->isBlocked())
      {
         Session::destroy('user');

         return error('Sorry You Account is blocked due to violation of Our Terms and conditions');
      }

      if (Auth::user()->isAdmin())
      {
         return redirectTo('templates\admin\manage\overview');
      }

       return redirectTo('templates\customer\booking\services');
  }


}
