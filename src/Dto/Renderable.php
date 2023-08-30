<?php

declare(strict_types=1);

namespace IWD\Templator\Dto;

class Renderable
{
    public function __construct(
        public readonly string $template,
        /**
         * @var array<string,mixed>
         */
        public readonly array $variables = [],
    ) {
    }
}
