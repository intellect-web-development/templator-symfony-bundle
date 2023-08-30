<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class KebabizeFilter implements FilterInterface
{
    public const KEBABIZE = 'kebabize';
    public const KEBAB = 'kebab';

    public function getCodes(): array
    {
        return [
            self::KEBABIZE,
            self::KEBAB,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::kebab($value);
    }
}