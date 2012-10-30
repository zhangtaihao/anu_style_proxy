<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StyleAsseticFiltersPass implements CompilerPassInterface
{
    /**
     * Adds Assetic filters for use in style aggregation.
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('anu_style_proxy.style_filter_processor') || !$container->hasDefinition('anu_style_proxy.aggregate_style_preprocessor')) {
            return;
        }

        // Verify filters.
        $filters = $container->getParameter('anu_style_proxy.style_filters');
        if (!empty($filters)) {
            foreach ($filters as $filter) {
                $filterServiceId = 'assetic.filter.'.$filter;
                if (!$container->hasDefinition($filterServiceId)) {
                    throw new InvalidArgumentException(sprintf('Invalid referenced filter "%s".', $filter));
                }
            }
        }

        // Inject filter list.
        $definition = $container->getDefinition('anu_style_proxy.style_filter_processor');
        $definition->replaceArgument(1, $filters);

        // Set filter on preprocessor.
        $definition = $container->getDefinition('anu_style_proxy.aggregate_style_preprocessor');
        $definition->addMethodCall('setFilter', array(new Reference('anu_style_proxy.style_filter_processor')));
    }
}
