<?php

declare(strict_types=1);

namespace IWD\Templator\Filters\Factory;

use IWD\Templator\Filters\FilterInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class FilterFactory
{
    /**
     * @var array<string, FilterInterface>
     */
    private array $filters = [];

    public function __construct(
        #[TaggedIterator(tag: 'templator.filter')]
        iterable $filters
    ) {
        foreach ($filters as $filter) {
            if (!$filter instanceof FilterInterface) {
                continue;
            }
            foreach ($filter->getCodes() as $code) {
                /** @var FilterInterface $filter */
                $this->filters[$code] = $filter;
            }
        }
    }

    public function getByCode(string $code): ?FilterInterface
    {
        return $this->filters[$code] ?? null;
    }
}
