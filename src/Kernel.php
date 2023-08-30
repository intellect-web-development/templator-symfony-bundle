<?php

declare(strict_types=1);

namespace IWD\Templator;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/** @codeCoverageIgnore */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function __construct(string $environment, bool $debug)
    {
        date_default_timezone_set('Europe/Moscow');
        //        $environment ='dev';
        $environment ='test';
        $debug= true;

        parent::__construct($environment, $debug);
    }

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $this->configureContainerForPath($container, __DIR__ . '/Resources/config');
    }

    public function configureContainerForPath(ContainerConfigurator $container, string $pathToConfig): void
    {
        $container->import($pathToConfig . '/{packages}/*.yaml');
        $container->import($pathToConfig . '/{packages}/' . $this->environment . '/*.yaml');

        if (is_file($pathToConfig . '/services.yaml')) {
            $container->import($pathToConfig . '/services.yaml');
            $container->import($pathToConfig . '/{services}_' . $this->environment . '.yaml');

            $container->import($pathToConfig . '/{services}/*.yaml');
            $container->import($pathToConfig . '/{services}/' . $this->environment . '/*.yaml');
        } elseif (is_file($path = $pathToConfig . '/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }
}
