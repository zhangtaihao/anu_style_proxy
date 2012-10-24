<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Validator\Constraints\UrlValidator;
use Symfony\Component\Validator\Constraints\Url;

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
            ->enumNode('proxy_mode')
                ->values(array('style', 'asset', 'combined'))
                ->treatNullLike('combined')
            ->end()
            ->scalarNode('backend_asset_server')->end()
            ->scalarNode('backend_style_server')->end()
            ->booleanNode('cache_backend_assets')->defaultFalse()->end();

        return $treeBuilder;
    }
}
