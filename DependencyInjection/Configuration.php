<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package PiWeb\PiCRUD\DependencyInjection
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pi_crud');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entities')
                    ->arrayPrototype()
                        ->children()
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
