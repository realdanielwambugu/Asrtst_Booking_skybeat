<?php

Namespace controllers\auth;

use controllers\core\Controller;

use support\proxies\Auth;

use support\proxies\Middleware;

use support\proxies\Hash;

use support\proxies\Validator;

use support\proxies\Event;

use http\Request;

use events\ConfirmResetPasswordEvent;

use support\proxies\Session;

class forgotPasswordController extends Controller
{

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
  * Validate and verify email
  *
  * @return string
  */
  public function confirmEmail(Request $credentials)
  {
      $validation = Validator::check($credentials, 'auth');

      if ($validation->fails())
      {
          return error($validation->errors()->first());
      }

      $user = $this->model('user')->where('email', '=', $credentials->email)->first();

      $user->code = rand(1000, 3000) + $user->id;

      $user->update(['code' => $user->code,]);

      Event::fire(new ConfirmResetPasswordEvent($user));

      return redirectTo('templates/customer/Auth/resetCode');
  }

  /**
  * Reset Password
  *
  * @return string
  */
  public function confirmCode(Request $credentials)
  {
      $validation = Validator::check($credentials, 'auth');

      if ($validation->fails())
      {
          return error($validation->errors()->first());
      }

      Session::set('PassRestCode', $credentials->code);

      return redirectTo('templates/customer/Auth/resetPassword');

  }


  /**
  * Reset Password
  *
  * @return string
  */
  public function resetPassword(Request $credentials)
  {

      $validation = Validator::check($credentials);

      if ($validation->fails())
      {
          return error($validation->errors()->first());
      }
      if (Session::has('PassRestCode'))
      {
         $user = $this->model('user')->where('code', '=', Session::get('PassRestCode'))->first();

         $user->update(['password' => Hash::make($credentials->password),]);

         Session::destroy('PassRestCode');
         
         return succes('Password Reset successful, You can now login with Your new password');
      }

      return error('Invalid Request: This may be as result of expired password reset code');

  }


}
