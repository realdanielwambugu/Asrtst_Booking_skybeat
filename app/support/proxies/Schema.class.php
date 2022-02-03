<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Schema extends proxy
{
  /**
  * get the accessor class.
  *
  * @return object app\database\builders\schema\Builder
  */
  public static function getAccessor()
  {
     return 'schema';
  }
}
