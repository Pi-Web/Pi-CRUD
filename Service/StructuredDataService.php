<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiCRUD\Transformer\ContainerStructuredDataTransformerFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

final class StructuredDataService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly RequestStack $requestStack,
        private readonly ContainerStructuredDataTransformerFactory $containerStructuredDataTransformerFactory,
    ) {
    }

    public function getStructuredData(object $object): ?string
    {
        $configuration = $this->requestStack->getCurrentRequest()->attributes->get('configuration');
        if (!isset($configuration['annotation']['structuredDataTransformer'])) {
            return null;
        }

        try {
            $transformer = $this->containerStructuredDataTransformerFactory->getTransformer($configuration['annotation']['structuredDataTransformer']);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return null;
        }

        return $this->serializer->serialize(
            $transformer->transform($object),
            'json'
        );
    }
}
