<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('geek_cell_imagekit');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('public_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('private_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('base_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->arrayNode('configurations')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('endpoint')->end()
                            ->scalarNode('signed')
                                ->defaultFalse()
                            ->end()
                            ->integerNode('expires')
                                ->min(1)
                                ->defaultFalse()
                            ->end()
                            ->arrayNode('transformation')
                                ->scalarPrototype()->end()
                            ->end()
                        ->end()
                        ->validate()
                            ->ifTrue(fn ($v) => $v['signed'] && !$v['expires'])
                            ->thenInvalid("Signed URLs must have an expiration time (via 'expires')")
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
