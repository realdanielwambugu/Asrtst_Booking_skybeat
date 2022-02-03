<?php

Namespace middleware;

use interfaces\MiddlewareInterface;

use http\Request;

use Closure;


class CheckAge implements MiddlewareInterface
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

  return d('ageeeee');

    return $next($request);
  }


}
