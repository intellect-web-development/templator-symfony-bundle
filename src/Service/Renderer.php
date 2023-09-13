<?php

declare(strict_types=1);

namespace IWD\Templator\Service;

use Adbar\Dot;
use IWD\Templator\Dto\Renderable;
use IWD\Templator\Dto\NormalizeVariable;
use IWD\Templator\Filters\Factory\FilterFactory;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class Renderer
{
    private readonly PropertyAccessor $propertyAccessor;

    public function __construct(
        private readonly FilterFactory $filterFactory,
    ) {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    public function render(Renderable $renderable): string
    {
        $normalizeVariables = [];
        preg_match_all('/{{(.+?)}}/', $renderable->template, $matches);
        foreach ($matches[0] as $match) {
            $normalizeVariables[] = new NormalizeVariable($match);
        }

        $template = $renderable->template;
        foreach ($normalizeVariables as $variable) {
            $targetValue = $this->propertyAccessor->getValue($renderable->variables, "[{$variable->targetVariable}]");
            if (null === $targetValue) {
                $targetValue = $this->propertyAccessor->getValue($renderable->variables, "[{$variable->rootTargetVariable}]");
            }

            if (is_object($targetValue)) {
                $preparedTargetValue = (string) $this->propertyAccessor->getValue($targetValue, $variable->withoutRootTargetVariable);
                $preparedTargetValue = $this->applyFilters($preparedTargetValue, $variable->filters);
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            } elseif (is_array($targetValue)) {
                $dotVariableValue = new Dot($targetValue, true);
                $preparedTargetValue = (string) $dotVariableValue[$variable->withoutRootTargetVariable];
                $preparedTargetValue = $this->applyFilters($preparedTargetValue, $variable->filters);
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            } elseif (is_string($targetValue) || is_numeric($targetValue)) {
                $preparedTargetValue = (string) $targetValue;
                $preparedTargetValue = $this->applyFilters($preparedTargetValue, $variable->filters);
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            } else {
                $preparedTargetValue = Renderable::BACKSPACE_SYMBOL;
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            }
        }

        return $this->clearEmptyLines($template);
    }

    /**
     * @param string $value
     * @param string[] $filters
     * @return string
     */
    private function applyFilters(string $value, array $filters): string
    {
        $resultValue = $value;
        foreach ($filters as $filter) {
            $resultValue = $this->filterFactory->getByCode($filter)?->apply($resultValue) ?? $resultValue;
        }

        return $resultValue;
    }

    private function clearEmptyLines(string $payload): string
    {
        return str_replace([
            Renderable::BACKSPACE_SYMBOL . PHP_EOL,
            PHP_EOL . Renderable::BACKSPACE_SYMBOL,
            Renderable::BACKSPACE_SYMBOL,
        ], '', $payload);
    }
}
