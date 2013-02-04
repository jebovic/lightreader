<?php

namespace LightReader\Config\Validator;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class AppConfigurationValidator implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lightreader');
        $rootNode
            ->children()
                ->scalarNode('title')
                    ->defaultValue('Light Reader')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('design')
                    ->defaultValue('html')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('nextLink')
                    ->defaultValue('Next')
                ->end()
                ->scalarNode('prevLink')
                    ->defaultValue('Previous')
                ->end()
                ->scalarNode('randomLink')
                    ->defaultValue('Random')
                ->end()
                ->scalarNode('infiniteScroll')
                    ->defaultValue(true)
                ->end()
                ->arrayNode('proxy')
                    ->children()
                        ->scalarNode('url')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('port')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('user')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('password')
                            ->defaultValue(false)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('cache')
                    ->children()
                        ->scalarNode('maxage')
                            ->defaultValue(0)
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}