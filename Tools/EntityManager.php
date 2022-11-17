<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Doctrine\ORM\EntityManagerInterface;
use PiWeb\PiCRUD\Config\EntityConfigInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityManager
{
    public function __construct(
        private readonly EntityDiscovery $entityDiscovery,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getEntities(): array
    {
        return $this->entityDiscovery->getEntities();
    }

    /**
     * @throws NotFoundHttpException|InvalidArgumentException
     */
    public function getEntity($name): array|EntityConfigInterface
    {
        $entities = $this->entityDiscovery->getEntities();
        if (isset($entities[$name])) {
            return $entities[$name];
        }

        throw new NotFoundHttpException('Entity not found.');
    }

    /**
     * @throws NotFoundHttpException
     */
    public function create(EntityConfigInterface $entityConfig): mixed
    {
        $class = $entityConfig->getEntityClass();
        if (!class_exists($class)) {
            throw new NotFoundHttpException('Entity class does not exist.');
        }
        return new $class($this->entityManager);
    }
}
