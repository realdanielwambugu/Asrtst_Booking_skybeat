<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Storage extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object \models\Booking
    */
    public static function getAccessor()
    {
         return \storage\FileManager::class;
    }
}
