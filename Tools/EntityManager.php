<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;

class EntityManager
{
    public function __construct(
        private readonly EntityDiscovery $discovery,
        private readonly EntityManagerInterface $em
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function getEntities(): array
    {
        return $this->discovery->getEntities();
    }

    /**
     * @throws Exception
     */
    public function getEntity($name): array
    {
        $entities = $this->discovery->getEntities();
        if (isset($entities[$name])) {
            return $entities[$name];
        }

        throw new Exception('Entity not found.');
    }

    /**
     * @throws Exception
     */
    public function create($name): mixed
    {
        $entities = $this->discovery->getEntities();
        if (array_key_exists($name, $entities)) {
            $class = $entities[$name]['class'];
            if (!class_exists($class)) {
                throw new Exception('Entity class does not exist.');
            }
            return new $class($this->em);
        }

        throw new Exception('Entity does not exist.');
    }
}
