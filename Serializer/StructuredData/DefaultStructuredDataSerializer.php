<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Serializer\StructuredData;

use PiWeb\PiCRUD\Exception\StructuredDataNotImplementedException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class DefaultStructuredDataSerializer
 * @package PiWeb\PiCRUD\StructuredData
 */
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
