<?php

declare(strict_types=1);

namespace vendor\DoctrineInflector\Rules\French;

use vendor\DoctrineInflector\Rules\Pattern;

final class Uninflected
{
    /**
     * @return Pattern[]
     */
    public static function getSingular() : iterable
    {
        yield from self::getDefault();
    }

    /**
     * @return Pattern[]
     */
    public static function getPlural() : iterable
    {
        yield from self::getDefault();
    }

    /**
     * @return Pattern[]
     */
    private static function getDefault() : iterable
    {
        yield new Pattern('');
    }
}