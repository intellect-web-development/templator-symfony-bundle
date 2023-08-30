<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class SingularizeFilter implements FilterInterface
{
    public const SINGULARIZE = 'singularize';
    public const SINGULAR = 'singular';

    public function getCodes(): array
    {
        return [
            self::SINGULARIZE,
            self::SINGULAR,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::singularize($value);
    }
}