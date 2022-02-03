<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Event extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object app\base\Application
    */
    public static function getAccessor()
    {
         return \events\core\Event::class;
    }
}
