<?php

namespace PiWeb\PiCRUD\DependencyInjection;

use PiWeb\PiCRUD\Transformer\ContainerStructuredDataTransformerFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\ServiceLocatorTagPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class StructuredDataTransformerCompilerPass implements CompilerPassInterface
{
    public const STRUCTURED_DATA_TRANSFORMER_TAG = 'pi_crud.transformer.structured_data';

    public function process(ContainerBuilder $container): void
    {
        $transformerIds = array_keys($container->findTaggedServiceIds(self::STRUCTURED_DATA_TRANSFORMER_TAG));

        $transformerReferences = array_map(static function ($id) {
            return new Reference($id);
        }, $transformerIds);

        $ref = ServiceLocatorTagPass::register($container, array_combine($transformerIds, $transformerReferences));

        $locatorDef = $container->getDefinition(ContainerStructuredDataTransformerFactory::class);
        $locatorDef->setArgument(0, $ref);
    }
}