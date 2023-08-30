<?php

declare(strict_types=1);

namespace IWD\Templator\Dto;

use IWD\Templator\Service\Renderer;

class Renderable
{
    private static ?Renderer $renderer;

    public function __construct(
        public readonly string $template,
        /**
         * @var array<string,mixed>
         */
        public readonly array $variables = [],
    ) {
        if (!isset(self::$renderer)) {
            self::$renderer = new Renderer();
        }
    }

    /**
     * @description Есть вероятность, что этот метод исчезнет, сейчас он тут для обратной совместимости.
     * @return string
     */
    public function render(): string
    {
        return self::$renderer?->render($this) ?? '';
    }
}
