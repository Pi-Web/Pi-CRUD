<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Doctrine\Common\Annotations\Reader;
use Exception;
use PiWeb\PiCRUD\Annotation\Entity;
use PiWeb\PiCRUD\Annotation\Property;
use Psr\Cache\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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
     * @param AdapterInterface $cache
     */
    public function __construct(
        private $rootDir,
        private Reader $annotationReader,
        private AdapterInterface $cache,
    ) {
        $this->directory = 'Entity';
    }

    /**
     * @return array
     * @throws InvalidArgumentException
     */
    public function getEntities(): array
    {
        if (!empty($this->entities)) {
            return $this->entities;
        }

        $item = $this->cache->getItem(self::ENTITIES_CACHE_KEY);
        if(!$item->isHit()) {
            try {
                $this->discoverEntities();
            } catch (Exception) {
            }
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
        $path = [
            sprintf('%s/src/%s', $this->rootDir, $this->directory),
            sprintf('%s/vendor/*/*/%s', $this->rootDir, $this->directory),
        ];

        $finder = new Finder();
        $finder->in($path)->name('*.php');

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = str_starts_with($file->getPathname(), sprintf('%s/src/%s', $this->rootDir, $this->directory)) ?
                sprintf('App\Entity\%s', $file->getBasename('.php')) :
                sprintf('PiWeb\PiCRUD\Entity\%s', $file->getBasename('.php'));
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
