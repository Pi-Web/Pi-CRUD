<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use PiWeb\PiCRUD\Exception\StructuredDataNotImplementedException;
use PiWeb\PiCRUD\Serializer\StructuredData\StructuredDataSerializerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class StructuredDataService
 * @package PiWeb\PiCRUD\Service
 */
final class StructuredDataService
{
    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function getStructuredData(object $object): ?string
    {
        try {
            $structuredData = $this->serializer->normalize($object, StructuredDataSerializerInterface::SERIALIZER_FORMAT_STRUCTURED_DATA);
        } catch (StructuredDataNotImplementedException) {
            return null;
        }

        return is_string($structuredData) ?
            $structuredData :
            null;
    }
}
