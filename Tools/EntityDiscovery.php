<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Tools;

use Exception;
use PiWeb\PiCRUD\Annotation\Entity;
use PiWeb\PiCRUD\Factory\EntityConfigFactory;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class EntityDiscovery
{
    private const ENTITIES_CACHE_KEY = 'cache.pi_crud.entity_discovery.entities';

    private readonly string $directory;
    private array $entities = [];

    public function __construct(
        private readonly string $rootDir,
        private readonly CacheItemPoolInterface $cacheItemPool,
        private readonly EntityConfigFactory $entityConfigFactory,
    ) {
        $this->directory = 'Entity';
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws InvalidArgumentException
     * @throws NotFoundExceptionInterface
     */
    public function getEntities(): array
    {
        if (!empty($this->entities)) {
            return $this->entities;
        }

        $cacheItem = $this->cacheItemPool->getItem(self::ENTITIES_CACHE_KEY);
        if (!$cacheItem->isHit()) {
            try {
                $this->discoverEntities();
            } catch (Exception $e) {
                dump($e); die;
            }

            $cacheItem->set($this->entities);
            $cacheItem->expiresAfter(604800);
            $this->cacheItemPool->save($cacheItem);
        }

        return $this->entities = $cacheItem->get();
    }

    /**
     * @throws ReflectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function discoverEntities(): void
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

            $annotation = $reflectionClass->getAttributes(Entity::class);
            if (!$annotation) {
                continue;
            }

            $propertiesReflectionAttributes = [];
            foreach ($reflectionClass->getProperties() as $property) {
                $reflectionAttributes = $property->getAttributes();
                if (empty($reflectionAttributes)) {
                    continue;
                }

                foreach ($reflectionAttributes as $reflectionAttribute) {
                    $propertiesReflectionAttributes[$property->name][$reflectionAttribute->getName()] = $reflectionAttribute->getArguments();
                }
            }

            $classAnnotationArgument = current($annotation)->getArguments();
            $classAnnotationArgument['class'] = $class;
            $classAnnotationArgument['name'] = strtolower($file->getBasename('.php'));

            $this->entities[$classAnnotationArgument['name']] = $this->entityConfigFactory->getConfig(
                $classAnnotationArgument,
                $propertiesReflectionAttributes
            );
        }
    }
}
