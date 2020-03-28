<?php

/*
 * This file is part of the FOSCKEditor Bundle.
 *
 * (c) 2018 - present  Friends of Symfony
 * (c) 2009 - 2017     Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Owp\OwpCrudAdmin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('owp_crud_admin');
        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('entities')
                ->arrayPrototype()
                    ->children()
                        ->scalarNode('label')->end()
                        ->scalarNode('class')->end()
                        ->scalarNode('formClass')->end()
                        ->scalarNode('permission')->end()
                        ->arrayNode('fields')
                        ->arrayPrototype()
                            ->children()
                                ->scalarNode('label')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
