<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Mail extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object support\mail\Mailer;
    */
    public static function getAccessor()
    {
         return \support\mail\Mailer::class;
    }
}
