<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Gate extends proxy
{
  /**
  * get the accessor class.
  *
  * @return object app\support\Configuration
  */
  public static function getAccessor()
  {
      return \auth\access\control\Gate::class;
  }

}
