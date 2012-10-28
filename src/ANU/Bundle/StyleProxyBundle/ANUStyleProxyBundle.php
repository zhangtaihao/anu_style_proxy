<?php

namespace ANU\Bundle\StyleProxyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ANUStyleProxyBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DependencyInjection\Compiler\CachePass());
        $container->addCompilerPass(new DependencyInjection\Compiler\RegisterProfileListenersPass());
    }
}
