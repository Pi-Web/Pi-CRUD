<?php

namespace PiWeb\PiCRUD\Tools;

use PiWeb\PiCRUD\Annotation\Entity;
use PiWeb\PiCRUD\Annotation\Property;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class EntityDiscovery
{
    /**
     * @var string
     */
    private string $namespace;

    /**
     * @var string
     */
    private string $directory;

    /**
     * @var Reader
     */
    private $annotationReader;

    /**
     * The Kernel root directory
     * @var string
     */
    private $rootDir;

    /**
     * @var array
     */
    private $entities = [];


    /**
     * EntityDiscovery constructor.
     *
     * @param $namespace
     *   The namespace of the entities
     * @param $directory
     *   The directory of the entities
     * @param $rootDir
     * @param Reader $annotationReader
     */
    public function __construct($rootDir, Reader $annotationReader)
    {
        $this->namespace = 'App\Entity';
        $this->annotationReader = $annotationReader;
        $this->directory = 'Entity';
        $this->rootDir = $rootDir;
    }

    /**
     * Returns all the exports
     */
    public function getEntities() {
        if (!$this->entities) {
            $this->discoverEntities();
        }

        return $this->entities;
    }

    /**
     * Discovers exports
     */
    private function discoverEntities() {
        $path = $this->rootDir . '/src/' . $this->directory;
        $finder = new Finder();
        $finder->files()->in($path);

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            $class = $this->namespace . '\\' . $file->getBasename('.php');
            $reflectionClass = new \ReflectionClass($class);

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
            $this->entities[$annotation->getName()] = [
                'class' => $class,
                'annotation' => $annotation,
                'properties' => $propertiesAnnotation
            ];
        }
    }
}
