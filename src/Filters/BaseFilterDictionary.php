<?php

declare(strict_types=1);

namespace IWD\Templator\Filters;

class BaseFilterDictionary
{
    public const CAMELIZE = CamelizeFilter::CAMELIZE;
    public const CAPITALIZE = CapitalizeFilter::CAPITALIZE;
    public const CLASSIFY = ClassifyFilter::CLASSIFY;
    public const CONSTANTIZE = ConstantizeFilter::CONSTANTIZE;
    public const KEBAB = KebabizeFilter::KEBAB;
    public const PLURALIZE = PluralizeFilter::PLURALIZE;
    public const SINGULARIZE = SingularizeFilter::SINGULARIZE;
    public const TABLEIZE = TableizeFilter::TABLEIZE;
    public const URLIZE = UrlizeFilter::URLIZE;
}
