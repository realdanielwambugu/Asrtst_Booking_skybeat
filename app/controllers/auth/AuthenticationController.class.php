<?php

Namespace controllers\auth;

use controllers\core\Controller;

use interfaces\SessionInterface;

use support\proxies\Config;

use support\proxies\Validator;

use support\proxies\Hash;


class AuthenticationController extends Controller
{
  protected $protected;

  protected $protect = 'user';

  protected $session;

  protected $request;

  protected $user;

  public function __construct(SessionInterface $session)
  {
     $this->session = $session;

     $this->protect();
  }

  public function protect($protect = null)
  {
     $protect = !is_null($this->protect) ? $this->protect : $protect;

     if (!is_array($protect))
     {
        return $this->protected = Config::get("auth.protect.{$protect}");
     }

     return $this->protected = $protect;
  }

  public function attempt($request, $constraints = 'auth')
  {
      $key = Config::get("auth.protect.{$this->protect}.key");

      $password = $request->password;

      unset($request->password);

      $validation = Validator::check($request, $constraints);

      if($validation->fails())
      {
          return $validation->errors()->first();
      }

      if (!$this->password($password, $request->$key, $key))
      {
          Validator::check(['password' => $password], 'auth');

          return $validation->errors()->first();
      }

      $this->authenticate($request);

      return $this->redirect();
  }

  public function password($password, $value, $key)
  {
     $model = $this->protected['model'];

     $user = call_user_func_array([new $model, 'where'], [$key, '=', $value])->first('password');

     return Hash::check($password, $user->password);
  }

  public function authenticate($request)
  {
      $model = $this->protected['model'];

      $key = $this->protected['key'];

      $params = [$key, [$request->$key]];

      $user = call_user_func_array([new $model, 'whereIn'], $params)->first();

      $sessionName = mb_strtolower(classBasename($model));

      $this->session->set($sessionName, ['model'=> $model,'id' => $user->id]);

      return $this;
  }

  public function get($type)
  {
     return $this->user($type);
  }


  public function user($type = null)
  {
      $type = !is_null($this->protect) ? $this->protect : $type;

      if (isset($this->user[$type]))
      {
         return $this->user[$type];
      }

      if ($this->check($type))
      {
         $id = $this->session->pull("{$type}.id");

         $model = $this->session->pull("{$type}.model");

         $user = call_user_func_array([new $model, 'find'], [$id]);

         return $this->user[$type] = $user;
      }

      return $this->session->get('user');
  }

  public function destroySession($type = null)
  {
     $type = !is_null($this->protect) ? $this->protect : $type;

     if ($this->check($type))
     {
        $this->session->destroy($type);
     }

      return redirectTo($this->redirect());
  }

  public function check($key = null)
  {
     $key = !is_null($this->protect) ? $this->protect : $key;

     return $this->session->has($key);
  }

  public function id()
  {
     return $this->check() ? $this->session->pull('user.id') : null;
  }


}
