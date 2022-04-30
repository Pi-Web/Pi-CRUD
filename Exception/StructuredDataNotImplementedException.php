<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Exception;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Polyfill\Intl\Icu\Exception\NotImplementedException;

/**
 * Class StructuredDataNotImplementedException
 * @package PiWeb\PiCRUD\Exception
 */
final class StructuredDataNotImplementedException extends \Exception {}
