<?php

Namespace auth\cypher;

use support\proxies\Config;


class Hash
{

    public function make($password)
    {
         return password_hash(
                $password,
                Config::get('auth.hash.algo'),
                ['cost' => Config::get('auth.hash.cost')],
                );
    }


    public function check($password, $hash)
    {
        return password_verify($password, $hash);
    }

}
