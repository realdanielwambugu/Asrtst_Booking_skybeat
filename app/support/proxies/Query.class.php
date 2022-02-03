<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Query extends proxy
{
  /**
  * get the accessor class.
  *
  * @return object app\database\builders\query\Builder
  */
  public static function getAccessor()
  {
     return \interfaces\QueryInterface::class;
  }
}
