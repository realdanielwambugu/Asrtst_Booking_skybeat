<?php

declare(strict_types=1);

namespace vendor\DoctrineInflector;

interface WordInflector
{
    public function inflect(string $word) : string;
}
