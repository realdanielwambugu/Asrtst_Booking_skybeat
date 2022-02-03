<?php
Namespace controllers\core;

use base\Application;

use support\proxies\Middleware;

use support\proxies\Config;

use exceptions\RuntimeException;

class Controller
{
  /**
   * app instance.
   *
   * @var object
   */
   protected $app;

 /**
  *  languange inflector for pluralization
  *
  * @var object
  */
  protected $inflector;

  /**
   * index/default model.
   *
   * @var string
   */
   private $model;

  /**
   * index/default view.
   *
   * @var string
   */
   private $view;

  /**
   *
   * @param  object $app.
   * @return void
   */
    public function setDependencies(Application $app)
    {
       $this->app = $app;

       $this->inflector = $this->app->make('inflector');
    }

    /**
    * assign middlewares
    *
    * @return void
    */

    public function middleware()
    {

    }

    public function handleMiddleware($request)
    {
       return Middleware::handle($request);
    }

  /**
   * set new $model if it exists.
   * instantiate object of $model.
   *
   * @param  $model.
   * @return object
   */
    protected function model($model)
    {
       $this->model = Config::get('default.paths.modelsPath') . $model;

       if (class_exists($this->model))
       {
           return $this->app->make($this->model);
       }

       throw new RuntimeException("Error: undifined model {$this->model}");
    }

  /**
   * get model class name depending on data
   *
   * @return string
   */
    protected function getmodelName($data)
    {
      $modelName = classBasename($this->model);

      if (is_array($data))
      {
          return $this->inflector->pluralize($modelName);
      }

      return $modelName;
    }


  /**
   * set new $view if it exists.
   * require in  $view file.
   * display/echo data on $view.
   *
   * @param  string $view.
   * @param  array $data.
   * @return void.
   */
    protected function view($view, $data)
    {
       $this->$view = Config::get('default.paths.viewsPath') . $view . '.php';

       if (file_exists($this->$view))
       {
           if ($this->model)
           {
               $modelName = mb_strtolower($this->getmodelName($data));

               $$modelName = $data;
           }

            require_once $this->$view;

            return ;
       }

       throw new RuntimeException("{$this->$view} not found");
    }



}
