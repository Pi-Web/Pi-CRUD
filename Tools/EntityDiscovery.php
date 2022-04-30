<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use PiWeb\PiCRUD\Annotation\Entity;
use PiWeb\PiCRUD\Annotation\Property;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * Class EntityDiscovery
 * @package PiWeb\PiCRUD\Tools
 */
class EntityDiscovery
{
    private const ENTITIES_CACHE_KEY = 'cache.pi_crud.entity_discovery.entities';

    /**
     * @var string
     */
    private string $namespace;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @var array
     */
    private array $entities = [];

    /**
     * EntityDiscovery constructor.
     *
     * @param $rootDir
     * @param Reader $annotationReader
     */
    public function __construct(
        private $rootDir,
        private Reader $annotationReader,
        private AdapterInterface $cache,
    ) {
        $this->namespace = 'App\Entity';
        $this->directory = 'Entity';
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getEntities(): array
    {
        if (!empty($this->entities)) {
            return $this->entities;
        }

        $item = $this->cache->getItem(self::ENTITIES_CACHE_KEY);
        if(!$item->isHit()) {
            $this->discoverEntities();
            $item->set($this->entities);
            $item->expiresAfter(604800);
            $this->cache->save($item);
        }

        return $this->entities = $item->get();
    }

    /**
     * @throws ReflectionException
     */
    private function discoverEntities()
    {
        $path = $this->rootDir . '/src/' . $this->directory;
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->namespace . '\\' . $file->getBasename('.php');
            $reflectionClass = new ReflectionClass($class);

            $annotation = $this->annotationReader->getClassAnnotation($reflectionClass, Entity::class);
            if (!$annotation) {
                continue;
            }

            $propertiesAnnotation = [];
            foreach ($reflectionClass->getProperties() as $property) {
                $propertyAnnotation = $this->annotationReader->getPropertyAnnotation($property, Property::class);

                if ($propertyAnnotation !== null) {
                    $propertiesAnnotation[$property->name] = $propertyAnnotation;
                }
            }

            /** @var Entity $annotation */
            $this->entities[$annotation->name] = [
                'class' => $class,
                'annotation' => $annotation,
                'properties' => $propertiesAnnotation
            ];
        }
    }
}
