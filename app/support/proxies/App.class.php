<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class App extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object app\base\Application
    */
    public static function getAccessor()
    {
         return \base\Application::class;
    }
}
