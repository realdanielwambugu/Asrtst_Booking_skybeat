<?php

Namespace services;

/**
 *
 */
class Report
{

  /**
  * @method __construct.
  *
  * setting error logging to be active.
  *
  * @return void;
  */
  public function __construct()
  {
      ini_set("log_errors", TRUE);
  }

  /**
  * @method error.
  *
  * define error log file from logs folder.
  * set the file with init_set() php function.
  * write the error on the file.
  *
  * @return void;
  */
  public function error($error)
  {
      $report_file = "logs/error.log";
      ini_set('error_log', $report_file);

      error_log($error);

      exit();
  }



}
