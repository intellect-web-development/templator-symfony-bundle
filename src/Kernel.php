<?php

declare(strict_types=1);

namespace IWD\Templator;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/** @codeCoverageIgnore */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

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

    /**
     * @psalm-suppress MixedOperand
     */
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
