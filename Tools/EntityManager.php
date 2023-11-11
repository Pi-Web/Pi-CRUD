<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityManager
{
    public function __construct(
        private readonly EntityDiscovery $discovery,
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getEntities(): array
    {
        return $this->discovery->getEntities();
    }

    /**
     * @throws NotFoundHttpException|InvalidArgumentException
     */
    public function getEntity($name): array
    {
        $entities = $this->discovery->getEntities();
        if (isset($entities[$name])) {
            return $entities[$name];
        }

        throw new NotFoundHttpException('Entity not found.');
    }

    /**
     * @throws NotFoundHttpException|InvalidArgumentException
     */
    public function create($name): mixed
    {
        $entities = $this->discovery->getEntities();
        if (array_key_exists($name, $entities)) {
            $class = $entities[$name]['class'];
            if (!class_exists($class)) {
                throw new NotFoundHttpException('Entity class does not exist.');
            }
            return new $class($this->em);
        }

        throw new NotFoundHttpException('Entity does not exist.');
    }
}
