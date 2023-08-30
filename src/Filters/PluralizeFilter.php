<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class PluralizeFilter implements FilterInterface
{
    public const PLURALIZE = 'pluralize';
    public const PLURAL = 'plural';

    public function getCodes(): array
    {
        return [
            self::PLURALIZE,
            self::PLURAL,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::pluralize($value);
    }
}