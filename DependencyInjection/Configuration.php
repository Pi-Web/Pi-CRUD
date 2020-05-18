<?php

namespace PiWeb\PiCRUD\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pi_crud');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entities')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->end()
                            ->scalarNode('class')->end()
                            ->scalarNode('formClass')->defaultValue('PiWeb\PiCRUD\Form\EntityFormType')->end()
                            ->scalarNode('permission')->end()
                            ->arrayNode('fields')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('id')->end()
                                    ->scalarNode('label')->end()
                                    ->scalarNode('icon')->end()
                                    ->booleanNode('list')
                                        ->defaultFalse()
                                    ->end()
                                ->end()
                            ->end()
                            ->end()
                            ->arrayNode('templates')
                                ->scalarPrototype()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->scalarPrototype()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
