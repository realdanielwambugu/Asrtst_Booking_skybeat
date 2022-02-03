<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Middleware extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object \middleware\core\Middleware.
    */
    public static function getAccessor()
    {
         return \middleware\core\Middleware::class;
    }
}
