<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Serializer\StructuredData;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AbstractStructuredDataSerializer
 * @package PiWeb\PiCRUD\StructuredData
 */
interface StructuredDataSerializerInterface
{
    public const SERIALIZER_FORMAT_STRUCTURED_DATA = 'structured_data';
}
