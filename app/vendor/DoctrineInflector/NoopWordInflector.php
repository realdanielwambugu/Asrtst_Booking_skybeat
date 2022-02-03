<?php

declare(strict_types=1);

namespace vendor\DoctrineInflector;

class NoopWordInflector implements WordInflector
{
    public function inflect(string $word) : string
    {
        return $word;
    }
}
