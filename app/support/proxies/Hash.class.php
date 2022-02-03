<?php

Namespace support\proxies;

use support\proxies\core\proxy;


class Hash extends proxy
{
    /**
    * get the accessor class.
    *
    * @return object app\auth\cypher\Hash,
    */
    public static function getAccessor()
    {
         return \auth\cypher\Hash::class;
    }
}
