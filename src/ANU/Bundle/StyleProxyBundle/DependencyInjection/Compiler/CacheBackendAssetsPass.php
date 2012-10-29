<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CacheBackendAssetsPass implements CompilerPassInterface
{
    /**
     * Disables the asset server if configured to not cache backend assets.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->getParameter('anu_style_proxy.cache_backend_assets')) {
            $container->removeDefinition('anu_style_proxy.asset_server');
        }
    }
}
