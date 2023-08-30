<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class TableizeFilter implements FilterInterface
{
    public const TABLEIZE = 'tableize';
    public const TABLE = 'table';

    public function getCodes(): array
    {
        return [
            self::TABLEIZE,
            self::TABLE,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::tableize($value);
    }
}