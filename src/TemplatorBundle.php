<?php

declare(strict_types=1);

namespace IWD\Symfony\TemplatorSymfonyBundle;

use IWD\Templator\Service\Inflector;
use IWD\Templator\Service\Renderer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TemplatorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->register(Renderer::class)->setPublic(true);
        $container->register(Inflector::class)->setPublic(true);
    }
}
