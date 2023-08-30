<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class ClassifyFilter implements FilterInterface
{
    public const CLASSIFY = 'classify';
    public const _CLASS = 'class';

    public function getCodes(): array
    {
        return [
            self::CLASSIFY,
            self::_CLASS,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::classify($value);
    }
}
