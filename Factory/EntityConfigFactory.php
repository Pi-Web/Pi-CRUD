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
        $configName = $classAnnotationArgument['name'];

        if (!isset($this->managedConfig[$configName])) {
            $this->managedConfig[$configName] = clone $this->container->get($configClass);
            $this->managedConfig[$configName]->initConfig($classAnnotationArgument, $propertyAnnotationArgument);
        }

        return $this->managedConfig[$configName];
    }
}
