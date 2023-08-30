<?php

declare(strict_types=1);

namespace IWD\Templator\Dto;

class NormalizeVariable
{
    public readonly string $raw;
    public readonly string $regular;
    public readonly string $targetVariable;
    public readonly string $rootTargetVariable;
    public readonly string $withoutRootTargetVariable;
    public readonly array $filters;

    public function __construct(
        string $raw,
    ) {
        $this->raw = $raw;
        $this->regular = trim(str_replace(' ', '', $raw), '{} ');
        $explodeRegular = explode('|', $this->regular);
        $this->targetVariable = (string) array_shift($explodeRegular);
        $this->filters = $explodeRegular;
        $targetVariableExplode = explode('.', $this->targetVariable);
        $this->rootTargetVariable = (string) array_shift($targetVariableExplode);
        $this->withoutRootTargetVariable = implode('.', $targetVariableExplode);
    }
}
