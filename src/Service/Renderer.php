<?php

declare(strict_types=1);

namespace IWD\Templator\Service;

use Adbar\Dot;
use IWD\Templator\Dto\Renderable;
use IWD\Templator\Dto\NormalizeVariable;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class Renderer
{
    private readonly PropertyAccessor $propertyAccessor;

    public function __construct()
    {
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
            }
            if (is_array($targetValue)) {
                $dotVariableValue = new Dot($targetValue, true);
                $preparedTargetValue = (string) $dotVariableValue[$variable->withoutRootTargetVariable];
                $preparedTargetValue = $this->applyFilters($preparedTargetValue, $variable->filters);
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            }
            if (is_string($targetValue) || is_numeric($targetValue)) {
                $preparedTargetValue = (string) $targetValue;
                $preparedTargetValue = $this->applyFilters($preparedTargetValue, $variable->filters);
                $template = str_replace($variable->raw, $preparedTargetValue, $template);
            }
        }

        return $template;
    }

    private function applyFilters(string $value, array $filters): string
    {
        $resultValue = $value;
        foreach ($filters as $filter) {
            $resultValue = match ($filter) {
                'urlize', 'url' => Inflector::urlize($resultValue),
                'camelize', 'camel' => Inflector::camelize($resultValue),
                'classify', 'class' => Inflector::classify($resultValue),
                'tableize', 'table' => Inflector::tableize($resultValue),
                'kebabize', 'kebab' => Inflector::kebab($resultValue),
                'capitalize', 'capital' => Inflector::capitalize($resultValue),
                'constantize', 'const' => Inflector::constantize($resultValue),
                'pluralize', 'plural' => Inflector::pluralize($resultValue),
                'singularize', 'singular' => Inflector::singularize($resultValue),
                default => $resultValue,
            };
        }

        return $resultValue;
    }
}
