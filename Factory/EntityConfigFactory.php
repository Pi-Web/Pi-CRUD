<?php

namespace PiWeb\PiCRUD\Factory;

use PiWeb\PiCRUD\Config\EntityConfigInterface;
use PiWeb\PiCRUD\Config\PiCrudEntityConfig;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class EntityConfigFactory
{
    /**
     * @param EntityConfigInterface[] $managedConfig
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private array $managedConfig = [],
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getConfig(array $classAnnotationArgument, array $propertyAnnotationArgument = []): EntityConfigInterface
    {
        $configClass = $classAnnotationArgument['config'] ?? PiCrudEntityConfig::class;

        if (!isset($this->managedConfig[$configClass])) {
            $this->managedConfig[$configClass] = $this->container->get($configClass);
            $this->managedConfig[$configClass]->initConfig($classAnnotationArgument, $propertyAnnotationArgument);
        }

        return $this->managedConfig[$configClass];
    }
}
