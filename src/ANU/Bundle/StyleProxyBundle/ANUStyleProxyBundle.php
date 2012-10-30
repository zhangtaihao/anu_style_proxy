<?php

namespace ANU\Bundle\StyleProxyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ANUStyleProxyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DependencyInjection\Compiler\CachePass());
        $container->addCompilerPass(new DependencyInjection\Compiler\CacheBackendAssetsPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterProfileListenersPass(), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterProfilePreprocessorsPass(), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
