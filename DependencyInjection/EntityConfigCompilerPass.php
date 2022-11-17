<?php

namespace PiWeb\PiCRUD\DependencyInjection;

use PiWeb\PiCRUD\Factory\EntityConfigFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class EntityConfigCompilerPass implements CompilerPassInterface
{
    public const ENTITY_CONFIG_TAG = 'pi_crud.config.entity';

    public function process(ContainerBuilder $container): void
    {
        $transformerIds = array_keys($container->findTaggedServiceIds(self::ENTITY_CONFIG_TAG));

        $transformerReferences = array_map(static function ($id) {
            return new Reference($id);
        }, $transformerIds);

        $ref = ServiceLocatorTagPass::register($container, array_combine($transformerIds, $transformerReferences));

        $entityConfigFactory = $container->getDefinition(EntityConfigFactory::class);
        $entityConfigFactory->setArgument(0, $ref);
    }
}