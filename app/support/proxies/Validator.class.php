<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Validator extends proxy
{
  /**
  * get the accessor class.
  *
  * @return object app\support\Configuration
  */
  public static function getAccessor()
  {
      return \auth\validation\Validator::class;
  }

}
