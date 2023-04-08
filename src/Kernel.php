<?php

namespace XM;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTENSIONS = '.{php,xml,yaml,yml}';
    private const EXT_TYPE_GLOB = 'glob';

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                /** @var BundleInterface $instance */
                $instance = new $class();
                yield $instance;
            }
        }
    }

    public function getProjectDir(): string
    {
        return \dirname(__DIR__);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', \PHP_VERSION_ID < 70400 || $this->debug);
        $container->setParameter('container.dumper.inline_factories', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);
        $loader->load($confDir.'/{packages}/'.$this->environment.'/*'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);

        $loader->load($confDir.'/{services}'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);
        $loader->load($confDir.'/{services}/default/**/*'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);
        $loader->load($confDir.'/{services}/*'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);
        $loader->load($confDir.'/{services}/'.$this->environment.'/**/*'.self::CONFIG_EXTENSIONS, self::EXT_TYPE_GLOB);
    }
}
