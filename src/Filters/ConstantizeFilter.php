<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class ConstantizeFilter implements FilterInterface
{
    public const CONSTANTIZE = 'constantize';
    public const CONST = 'const';

    public function getCodes(): array
    {
        return [
            self::CONSTANTIZE,
            self::CONST,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::constantize($value);
    }
}
