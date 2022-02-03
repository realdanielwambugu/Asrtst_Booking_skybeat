<?php

Namespace middleware;

use interfaces\MiddlewareInterface;

use http\Request;

use Closure;

use support\proxies\Auth;


class Guest implements MiddlewareInterface
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
    if (!Auth::check())
    {
         return redirectTo('templates\customer\Auth\login');
    }

    return $next($request);
  }


}
