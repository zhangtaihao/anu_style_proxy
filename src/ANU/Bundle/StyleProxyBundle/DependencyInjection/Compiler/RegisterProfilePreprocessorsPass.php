<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RegisterProfilePreprocessorsPass implements CompilerPassInterface
{
    /**
     * Registers services tagged with 'anu_style_proxy.profile_preprocessor'.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('anu_style_proxy.profile_preprocess')) {
            return;
        }

        $definition = $container->getDefinition('anu_style_proxy.profile_preprocess');

        $preprocessorInfo = array();
        foreach ($container->findTaggedServiceIds('anu_style_proxy.profile_preprocessor') as $id => $tags) {
            $class = $container->getDefinition($id)->getClass();

            $refClass = new \ReflectionClass($class);
            $interface = 'ANU\Bundle\StyleProxyBundle\Proxy\Profile\Preprocessor';
            if (!$refClass->implementsInterface($interface)) {
                throw new \InvalidArgumentException(sprintf('Service "%s" must implement interface "%s".', $id, $interface));
            }

            foreach ($tags as $attributes) {
                $attributes += array('priority' => 0);
                $preprocessorInfo[$id] = $attributes;
            }
        }

        // Add info to constructor arguments.
        $definition->replaceArgument(1, $preprocessorInfo);
    }
}
