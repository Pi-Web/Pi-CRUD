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
abstract class AbstractStructuredDataSerializer implements NormalizerInterface, ContextAwareNormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        protected RouterInterface $router,
    ) {
    }

    abstract public function normalize($object, string $format = null, array $context = []): string;

    abstract public function supportsNormalization($data, string $format = null, array $context = []): bool;

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
