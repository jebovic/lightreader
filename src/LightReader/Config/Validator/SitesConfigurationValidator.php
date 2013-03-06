<?php

namespace LightReader\Config\Validator;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Configuration validator for sites settings
 */
class SitesConfigurationValidator implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lightreader_sites');
        $rootNode
            ->useAttributeAsKey('name')
            ->prototype('array')
            ->children()
                ->scalarNode('title')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('shortTitle')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('urlPattern')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('urlFirstPage')
                    ->isRequired()
                    ->defaultValue(1)
                ->end()
                ->scalarNode('urlStep')
                    ->isRequired()
                    ->defaultValue(1)
                ->end()
                ->scalarNode('grabSelector')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('allowedTags')
                    ->defaultValue('')
                ->end()
                ->scalarNode('routeName')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('randomRouteName')->end()
                ->scalarNode('urlRandom')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}