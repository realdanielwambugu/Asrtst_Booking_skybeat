<?php

Namespace auth\access\control;

use exceptions\InvalidArgument;

use exceptions\RuntimeException;

use exceptions\Unauthorized;

use base\Application;

use support\proxies\Auth;

use auth\access\control\Response;

use support\proxies\Config;


class Gate
{
  /**
 * Application instance
 *
 * @var object
 */
  protected $app;

  /**
 * Response class instance
 *
 * @var object
 */
  protected $response;

  /**
 * Authenticated user instance
 *
 * @var object
 */
  protected $user;


  /**
 * registred policies
 *
 * @var array
 */
  protected $policies = [];

  /**
 * array of registred abilities closure objects
 *
 * @var array
 */
  protected $abilities = [];

  /**
 * The callback to be used to guess policy names.
 *
 * @var callable|null
 */
 protected $guessPolicyNamesUsingCallback;


  public function __construct(Application $app, Response $response)
  {
      $this->app = $app;

      $this->response = $response;

      $this->guessPolicyNamesUsingCallback = null;

      $this->setRegisteredPolicies();
  }


  public function define($ability, $callBack)
  {
     if (is_callabe($callBack))
     {
         return $this->abilities[$ability] = $callBack;
     }

     if (is_string($callBack) && contains($callBack, "@"))
     {
          $extract = cutString($callBack, '@', 'both');

          $this->abilities[$ability] = [
               'Policy'  => $extract['start'],
               'ability' => $extract['end'],
             ];

             return $this;
     }

     throw new InvalidArgument('Callback must be a callable or a Class@method string');

  }


  public function setRegisteredPolicies()
  {
      $this->policies = $this->app->make('policies');
  }


  public function allows($ability, $instance = null)
  {
      if ($this->hasAbility($ability))
      {
         $ability = $this->abilities[$ability];
      }
      else
      {
         $modelClass = !is_string($instance) ? get_class($instance) : $instance;

         $ability = $this->handlePolicy($ability, $modelClass);
      }

      $user = !$this->user ? Auth::user() : $this->user;

      return  call_user_func($ability, $user, $instance);
  }

  public function denies($ability, $instance = null)
  {
     return !$this->allows($ability, $instance);
  }

  public function forUser($user)
  {
     $this->user = $user;

     return $this;
  }

  public function any(array $abilities, $instance = null)
  {
     foreach ($abilities as $ability)
     {
         if ($this->allows($ability, $instance))
         {
             return true;
         }
     }

     return false;
  }


  public function none(array $abilities, $instance = null)
  {
      return !$this->any($abilities, $instance);
  }


  public function authorize($abilities, $instance = null)
  {
      if (!$this->allows($abilities, $instance))
      {
         throw new Unauthorized("403 Action Forbidden", 403);
      }
  }

  public function check($ability, array $flags)
  {
     array_unshift($flags, Auth::user());

     if ($this->hasAbility($ability))
     {
         return call_user_func_array($this->abilities[$ability], $flags);
     }

     throw new RuntimeException("Ability {$ability} is not registred with the Gate");
  }


  public function inspect($abilities, $instance = null)
  {
     $this->allows($abilities, $instance);

     return $this->response;
  }


  public function handlePolicy($ability, $modelClass)
  {
    if (is_array($ability))
    {
        return [$this->resolvePolicy($ability['Policy']), $ability['ability']];
    }

    if ($policy = $this->guessPolicyName($modelClass))
    {
        return [$this->resolvePolicy($policy), $ability];
    }

    if ($this->hasPolicy($modelClass))
    {
        return [$this->resolvePolicy($this->policies[$modelClass]), $ability];
    }

     throw new RuntimeException("Policy for {$modelClass} not found or ability {$ability} not set");
  }

  public function guessPolicyName($modelClass)
  {
     $path = Config::get('default.paths.policyPath');

     $ShortClassName = classBasename($modelClass);

     $policy = $ShortClassName . 'Policy';

     if ($this->guessPolicyNamesUsingCallback)
     {
        $policy = call_user_func($this->guessPolicyNamesUsingCallback, $ShortClassName);
     }

     return class_exists($path . $policy) ? $path . $policy : false;
  }

  public function guessPolicyNamesUsing($callBack)
  {
      $this->guessPolicyNamesUsingCallback = $callBack;
  }



  public function resolvePolicy($policy)
  {
      return new $policy;
  }


  public function hasPolicy($model)
  {
     return array_key_exists($model, $this->policies);
  }


  public function hasAbility($ability)
  {
      return array_key_exists($ability, $this->abilities);
  }

}
