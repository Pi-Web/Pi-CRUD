<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Exception;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionException;

/**
 * Class EntityManager
 * @package PiWeb\PiCRUD\Tools
 */
class EntityManager
{
    public function __construct(
        private EntityDiscovery $discovery,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * Returns a list of available entity.
     *
     * @return array
     * @throws ReflectionException
     */
    public function getEntities(): array
    {
        return $this->discovery->getEntities();
    }

    /**
     * Returns one entity by name
     *
     * @param $name
     * @return array
     *
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
     * Creates an entity
     *
     * @param $name
     *
     * @return mixed
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
