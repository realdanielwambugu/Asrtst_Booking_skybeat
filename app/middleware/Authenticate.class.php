<?php

Namespace middleware;

use interfaces\MiddlewareInterface;

use http\Request;

use Closure;

use support\proxies\Auth;


class Authenticate implements MiddlewareInterface
{

  /**
  * Handle an incoming request.
  *
  * @param  object Http\Request  $request
  * @param  object Closure  $next
  * @return mixed
  */
  public function __invoke(Request $request, Closure $next)
  {
    if (Auth::check())
    {
        if (Auth::user()->isAdmin())
        {
           return redirectTo('templates\admin\manage\overview');
        }

         return redirectTo('templates\customer\booking\services');
    }

    return $next($request);
  }


}
