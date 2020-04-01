<?php

namespace PiWeb\PiCRUD\Tools;

use PiWeb\PiCRUD\Service\Model\ModelManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Search\QueryBuilder as EasyadminQueryBuilder;

class EntityManager
{
    /**
     * @var EntityDiscovery
     */
    private $discovery;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityDiscovery $discovery, EntityManagerInterface $em)
    {
        $this->discovery = $discovery;
        $this->em = $em;
    }

    /**
     * Returns a list of available entity.
     *
     * @return array
     */
    public function getEntities() {
        return $this->discovery->getEntities();
    }

    /**
     * Returns one entity by name
     *
     * @param $name
     * @return array
     *
     * @throws \Exception
     */
    public function getEntity($name) {
        $entities = $this->discovery->getEntities();
        if (isset($entities[$name])) {
            return $entities[$name];
        }

        throw new \Exception('Entity not found.');
    }

    /**
     * Creates a entity
     *
     * @param $name
     * @return EntityInterface
     *
     * @throws \Exception
     */
    public function create($name) {
        $entities = $this->discovery->getEntities();
        if (array_key_exists($name, $entities)) {
            $class = $entities[$name]['class'];
            if (!class_exists($class)) {
                throw new \Exception('Entity class does not exist.');
            }
            return new $class($this->em);
        }

        throw new \Exception('Entity does not exist.');
    }
}
