<?php

Namespace Http;

use base\Application;

use middleware\core\Middleware;


class Dispatcher
{
  /**
   * app instance.
   *
   * @var object
   */
   private $app;

  /**
   * Middleware instance.
   *
   * @var object
   */
   private $middleware;

  /**
   * set the application  instance.
   *
   * @param object app\base\Application
   * @return void
   */
   public function __construct(Application $app, Middleware $middleware)
   {
        $this->app = $app;

        $this->middleware = $middleware;
   }

  /**
   * call controller method
   *
   * @param object app\http\Request
   * @return mixed
   */
    public function dispatch(Request $request)
    {
       $request->set();

       $this->middleware->request($request);

       $instance = $this->resolveController($request->controller);

       $method = $request->method;


       if($middleware = $this->middleware($request, $instance))
       {
           return $this->response($middleware);
       }

       unset($request->method, $request->controller);

       return $this->response(call_user_func([$instance, $method], $request));
    }

  /**
   * handle middleware
   *
   * @param object app\http\Request
   * @return mixed
   */
    public function middleware(Request $request, $instance)
    {
       call_user_func([$instance, 'middleware']);

       return call_user_func([$instance, 'handleMiddleware'], $request);
    }

  /**
   * Display response
   *
   * @param mixed $message;
   * @return mixed
   */
    public function response($message)
    {
       $response = new Response;

       return $response->message($message)->send();

    }

  /**
   * Create an instance of controller class
   *
   * @return object
   */
    protected function resolveController($controller)
    {
        return $this->app->make($controller);
    }

}
