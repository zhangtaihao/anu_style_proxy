<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('anu_style_proxy');

        $rootNode->children()
            ->scalarNode('backend_style_server')->isRequired()->end()
            ->scalarNode('style_server_base')->end()
            ->booleanNode('process_resources')->defaultFalse()->end()
            ->booleanNode('test')->end();

        return $treeBuilder;
    }
}
