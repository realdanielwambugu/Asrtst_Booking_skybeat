<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Session extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object auth\access\state
    */
    public static function getAccessor()
    {
         return \interfaces\SessionInterface::class;
    }
}
