<?php

namespace Dende\FrontBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root = $treeBuilder->root('frontBundle');

        $root
            ->children()
                ->arrayNode('links')->isRequired()
                    ->children()
                        ->scalarNode('contact_mail')->isRequired()->end()
                        ->scalarNode('facebook')->isRequired()->end()
                        ->scalarNode('linkedin')->isRequired()->end()
                        ->scalarNode('goldenline')->isRequired()->end()
                        ->scalarNode('github')->isRequired()->end()
                        ->scalarNode('youtube')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
