<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class StructuredDataService
 * @package PiWeb\PiCRUD\Service
 */
final class StructuredDataService
{
    public const SERIALIZER_FORMAT_STRUCTURED_DATA = 'structured_data';

    public function __construct(
        private SerializerInterface $serializer,
    ) {
    }

    public function getStructuredData(object $object): ?string
    {
        try {
            $structuredData = $this->serializer->normalize($object, self::SERIALIZER_FORMAT_STRUCTURED_DATA);
        } catch (NotEncodableValueException) {
            return null;
        }

        return is_string($structuredData) ?
            $structuredData :
            null;
    }
}
