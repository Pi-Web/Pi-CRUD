<?php

namespace PiWeb\PiCRUD\Transformer;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class ContainerStructuredDataTransformerFactory
{
    public function __construct(
        private readonly ContainerInterface $container,
        private array $managedTransformers = [],
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getTransformer(string $transformerClass): StructuredDataTransformerInterface
    {
        if (!isset($this->managedTransformers[$transformerClass])) {
            $this->managedTransformers[$transformerClass] = $this->container->get($transformerClass);
        }

        return $this->managedTransformers[$transformerClass];
    }
}
