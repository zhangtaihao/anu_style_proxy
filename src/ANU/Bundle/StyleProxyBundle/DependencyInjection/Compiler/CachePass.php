<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CachePass implements CompilerPassInterface
{
    /**
     * Sets up the cache delegate.
     */
    public function process(ContainerBuilder $container)
    {
        $cacheId = $container->getParameter('anu_style_proxy.cache_id');
        if (!$container->hasDefinition($cacheId)) {
            throw new InvalidArgumentException('Supplied cache ID is not defined: '.$cacheId);
        }

        $container->setAlias('anu_style_proxy.cache', $cacheId);
    }
}
