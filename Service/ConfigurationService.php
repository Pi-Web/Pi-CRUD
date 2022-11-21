<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Exception;
use PiWeb\PiCRUD\Config\EntityConfigInterface;
use PiWeb\PiCRUD\Tools\EntityManager;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ConfigurationService
{
    public function __construct(
        private readonly EntityManager $entityManager,
    ) {
    }

    public function getEntityConfiguration(string $type): array|EntityConfigInterface
    {
        try {
            return $this->entityManager->getEntity($type);
        } catch (Exception|InvalidArgumentException) {
            throw new NotFoundHttpException();
        }
    }
}
