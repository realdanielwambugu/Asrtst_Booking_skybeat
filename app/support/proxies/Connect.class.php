<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Connect extends proxy
{
  /**
  * get the accessor class.
  *
  * @return object app\database\Connection
  */
  public static function getAccessor()
  {
     return \interfaces\ConnectionInterface::class;
  }
}
