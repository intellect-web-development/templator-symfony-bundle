<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class CapitalizeFilter implements FilterInterface
{
    public const CAPITALIZE = 'capitalize';
    public const CAPITAL = 'capital';

    public function getCodes(): array
    {
        return [
            self::CAPITALIZE,
            self::CAPITAL,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::capitalize($value);
    }
}
