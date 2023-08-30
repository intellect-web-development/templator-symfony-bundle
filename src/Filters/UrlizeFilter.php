<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use IWD\Templator\Service\Inflector;

class UrlizeFilter implements FilterInterface
{
    public const URLIZE = 'urlize';
    public const URL = 'url';

    public function getCodes(): array
    {
        return [
            self::URLIZE,
            self::URL,
        ];
    }

    public function apply(string $value): string
    {
        return Inflector::urlize($value);
    }
}
