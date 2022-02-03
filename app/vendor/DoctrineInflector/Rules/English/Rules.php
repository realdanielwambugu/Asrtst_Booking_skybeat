<?php

declare(strict_types=1);

namespace vendor\DoctrineInflector\Rules\English;

use vendor\DoctrineInflector\Rules\Patterns;
use vendor\DoctrineInflector\Rules\Ruleset;
use vendor\DoctrineInflector\Rules\Substitutions;
use vendor\DoctrineInflector\Rules\Transformations;

final class Rules
{
    public static function getSingularRuleset() : Ruleset
    {
        return new Ruleset(
            new Transformations(...Inflectible::getSingular()),
            new Patterns(...Uninflected::getSingular()),
            (new Substitutions(...Inflectible::getIrregular()))->getFlippedSubstitutions()
        );
    }

    public static function getPluralRuleset() : Ruleset
    {
        return new Ruleset(
            new Transformations(...Inflectible::getPlural()),
            new Patterns(...Uninflected::getPlural()),
            new Substitutions(...Inflectible::getIrregular())
        );
    }
}
