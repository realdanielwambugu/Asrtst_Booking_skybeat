<?php

Namespace middleware\core;

use support\proxies\config;

use interfaces\MiddlewareInterface;

use http\Request;

use exceptions\RuntimeException;

class Middleware
{
  protected $app;

  protected $start;

  protected $ignore = [];

  protected $aliases = [];

  protected $alias;

  protected $request;

  protected $onlyMethods = [];

  protected $exceptMethods = [];

  public function __construct($app)
  {
     $this->app = $app;

     $this->start = function ()
     {
        return false;
     };
  }

  public function request(Request $request)
  {
      $this->request = $request;
  }


  public function pushMiddleware(MiddlewareInterface $middleware, $alias)
  {
      $next = $this->start;

      $only = $this->onlyMethods;

      $except = $this->exceptMethods;

      $this->start = function ($request) use ($middleware, $next, $alias, $only, $except)
      {
           $middlewareApplyToMethod  = true;

           if (isset($only[$alias]))
           {
               $bool = in_array($request->method, $only[$alias]);
           }

           if (isset($except[$alias]))
           {
               if (in_array($request->method, $except[$alias]))
               {
                   $middlewareApplyToMethod = false;
               }
           }

           if ($middlewareApplyToMethod)
           {
                return $middleware($request, $next);
           }

      };
  }


  protected function resolveMiddlware($middleware)
  {
      if (is_string($middleware))
      {
         if (class_exists($middleware))
         {
             return  $this->app->make($middleware);;
         }

      }

      throw new RuntimeException("{$middleware} not found");
  }

  public function getMiddleware($middleware)
  {
     return config::get("middleware.{$middleware}");
  }

  public function registerMiddlewares(array $middlewares)
  {
     foreach ($middlewares as $middleware)
     {
         if (!$this->ignore($middleware, true))
         {
             $this->pushMiddleware($this->resolveMiddlware($middleware), $middleware);
         }
     }

  }


  public function registerMiddleware($middleware)
  {
     if (contains($middleware, 'Group:'))
     {
         $group = 'groups.' .  Cutstring($middleware, ':', false);

         return $this->registerMiddlewares($this->getMiddleware($group));
     }

     $instance = $this->resolveMiddlware($this->getMiddleware($middleware));

     return $this->pushMiddleware($instance, $middleware);
  }


  public function ignore($middleware, $check = false)
  {
    if ($check)
    {
      return in_array($middleware, $this->ignore);
    }

     $middlewares = func_get_args();

     foreach ($middlewares as $middleware)
     {
         $path = Config::get('default.paths.middlewarePath');

         $this->ignore[] = $path . classBasename($middleware);
     }

     return $this;
  }


  public function assign($middleware, $callaback = null)
  {
      if (is_callabe($callaback))
      {
           $callaback($this, $this->request);
      }

      $this->alias = $middleware;

      $this->aliases[] = $middleware;

      return $this;
  }

  public function only()
  {
     $onlyMethods = func_get_args();

     $this->onlyMethods[$this->alias] = $onlyMethods;

     return $this;
  }

  public function except()
  {
    $exceptMethods = func_get_args();

    $this->exceptMethods[$this->alias] = $exceptMethods;

    return $this;
  }


  public function handle(Request $request)
  {
      foreach ($this->aliases as $alias)
      {
         $this->registerMiddleware($alias);
      }

      return call_user_func($this->start, $request);
  }

}
