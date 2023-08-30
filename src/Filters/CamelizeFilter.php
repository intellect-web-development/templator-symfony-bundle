<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class CamelizeFilter implements FilterInterface
{
    public const CAMELIZE = 'camelize';
    public const CAMEL = 'camel';

    public function getCodes(): array
    {
        return [
            self::CAMELIZE,
            self::CAMEL,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::camelize($value);
    }
}
