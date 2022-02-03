<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Response extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object app\base\Application
    */
    public static function getAccessor()
    {
         return \auth\access\control\Response::class;
    }
}
