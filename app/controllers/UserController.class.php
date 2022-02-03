<?php

Namespace controllers;

use controllers\core\Controller;

use support\proxies\Middleware;

use support\proxies\Event;

use support\proxies\Auth;

use support\proxies\Validator;

use support\proxies\Storage;

use http\Request;

use support\proxies\Hash;



class UserController extends Controller
{

  protected $valid = false;

  /**
  * assign middlewares
  *
  * @return void
  */
  public function middleware()
  {
      Middleware::assign('admin')->except('show', 'update', 'changePassword');

      Middleware::assign('guest')->except('validate','create');
  }

  public function index(Request $request)
  {
      $users = $this->model('user')->all();

      return $this->view('customer', $users);
  }

  public function validate(Request $request, $constraints = 'default')
  {
      $validation = Validator::check($request, $constraints);

      if ($validation->fails())
      {
          return $validation->errors()->first();
      }

      return false;
  }

  public function create(Request $request)
  {
       $request->status = 'active';

      if ($error = $this->validate($request))
      {
         return $error;
      }

      unset($request->confirmPassword);

      $request->password = Hash::make($request->password);

      $user = $this->model('user')->create($request);

      return redirectTo('templates\customer\Auth\login');

  }

  public function show(Request $request)
  {
       $user = $this->model("user")->find(Auth::id());

      if ($request->for == 'customer')
      {
          if ($request->temp == 'header')
          {
             return $this->view('partials/customerHeader', $user);
          }

          return $this->view('customserAccount', $user);
      }

      if ($request->for == 'header')
      {
         return $this->view('partials/adminHeader', $user);
      }

      return $this->view('partials/adminWelcome', $user);
  }

  public function update(Request $request)
  {
    if ($error = $this->validate($request, 'userUpdate'))
    {
       return error($error);
    }

    $user = $this->model('User')->find($request->id);

    if (isset($request->for))
    {
        if ($request->photo['name'])
        {
            $photo = Storage::pushFile($request->photo, 'images.user');

            $request->photo = $photo->uniqueName;
        }
        else
        {
           unset($request->photo);
        }

        $customer = $request->for;

        unset($request->password, $request->newPassword, $request->for);
    }

    $user->update($request);

    return isset($customer) ? succes('account updated') : $this->index($request);
  }

  public function changePassword(Request $request)
  {
      if ($error = $this->validate($request, 'changePassword'))
      {
         return error($error);
      }

      $user = $this->model('User')->find($request->id);

      if (!Hash::check($request->password, $user->password))
      {
         return error('You have entered wrong password');
      }

      $user->password = $request->newPassword;

      $user->save();

      return succes('Password Changed succesfully');
  }

  public function delete(Request $request)
  {
       $user = $this->model('User')->find($request->id);

       $user->delete();

       return $this->index($request);
  }


}
