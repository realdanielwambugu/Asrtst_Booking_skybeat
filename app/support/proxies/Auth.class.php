<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Auth extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object \controllers\auth\AuthenticationController
    */
    public static function getAccessor()
    {
         return \controllers\auth\AuthenticationController::class;
    }
}
