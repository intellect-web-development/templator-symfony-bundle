<?php

declare(strict_types=1);

namespace IWD\Templator\Dto;

class Renderable
{
    public const BACKSPACE_SYMBOL = '___%%%__[@[%back_space_symbol%]@]__%%%___';
    public array $preparedVariables;

    public function __construct(
        public readonly string $template,
        /**
         * @var array<string,mixed>
         */
        public readonly array $variables = [],
    ) {
        $this->preparedVariables = array_map(
            static function (mixed $value): mixed {
                if (empty($value)) {
                    return self::BACKSPACE_SYMBOL;
                }

                return $value;
            },
            array_values($variables)
        );
    }
}
