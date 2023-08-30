<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('templator.filter')]
interface FilterInterface
{
    /**
     * @return string[]
     * @description Aliases in template
     */
    public function getCodes(): array;

    /**
     * @description Filter code
     */
    public function apply(string $value): string;
}
