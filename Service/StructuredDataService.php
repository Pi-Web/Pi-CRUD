<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiCRUD\Config\EntityConfigInterface;
use PiWeb\PiCRUD\Enum\Crud\EntityOptionEnum;
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
        /** @var EntityConfigInterface $configuration */
        $configuration = $this->requestStack->getCurrentRequest()->attributes->get('configuration');
        if (empty($transformerClass = $configuration->getOption(EntityOptionEnum::TRANSFORMER_STRUCTURED_DATA))) {
            return null;
        }

        try {
            $transformer = $this->containerStructuredDataTransformerFactory->getTransformer($transformerClass);
        } catch (NotFoundExceptionInterface|ContainerExceptionInterface) {
            return null;
        }

        return $this->serializer->serialize(
            $transformer->transform($object),
            'json'
        );
    }
}
