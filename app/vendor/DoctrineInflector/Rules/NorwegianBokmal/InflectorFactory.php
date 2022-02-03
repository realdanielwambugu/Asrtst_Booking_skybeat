<?php

declare(strict_types=1);

namespace vendor\DoctrineInflector\Rules\NorwegianBokmal;

use vendor\DoctrineInflector\GenericLanguageInflectorFactory;
use vendor\DoctrineInflector\Rules\Ruleset;

final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : Ruleset
    {
        return Rules::getSingularRuleset();
    }

    protected function getPluralRuleset() : Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
