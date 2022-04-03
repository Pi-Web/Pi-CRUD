<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Service;

use Exception;
use PiWeb\PiCRUD\Tools\EntityManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ConfigurationService
 * @package PiWeb\PiCRUD\Service
 */
final class ConfigurationService
{
    public function __construct(
        private EntityManager $entityManager,
    ) {
    }

    public function getEntityConfiguration(string $type): array
    {
        try {
            return $this->entityManager->getEntity($type);
        }
        catch (Exception) {
            throw new NotFoundHttpException();
        }
    }
}
