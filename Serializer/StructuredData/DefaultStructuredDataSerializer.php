<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Serializer\StructuredData;

use PiWeb\PiCRUD\Exception\StructuredDataNotImplementedException;

final class DefaultStructuredDataSerializer extends AbstractStructuredDataSerializer
{
    public function normalize($object, string $format = null, array $context = []): string
    {
        throw new StructuredDataNotImplementedException();
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return StructuredDataSerializerInterface::SERIALIZER_FORMAT_STRUCTURED_DATA === $format;
    }
}
