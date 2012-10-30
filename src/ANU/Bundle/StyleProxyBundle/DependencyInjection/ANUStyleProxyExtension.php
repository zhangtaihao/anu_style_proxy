<?php

namespace ANU\Bundle\StyleProxyBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ANUStyleProxyExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!empty($config['test'])) {
            $loader->load('test.yml');
        }

        $container->setParameter('anu_style_proxy.backend_style_base', $config['backend_style_server']);
        if (!empty($config['style_server_base'])) {
            $container->setParameter('anu_style_proxy.style_base', $config['style_server_base']);
        }

        $this->registerStyleServerConfiguration($config, $container, $loader);
        $this->registerAssetServerConfiguration($config, $container, $loader);
    }

    private function registerStyleServerConfiguration($config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('style_server.yml');

        // Use 'process_resources' as default parameter value.
        if (!$container->hasParameter('anu_style_proxy.preprocess_styles')) {
            $container->setParameter('anu_style_proxy.preprocess_styles', $config['process_resources']);
        }
    }

    private function registerAssetServerConfiguration($config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('asset_server.yml');

        // Use 'process_resources' as default parameter value.
        $parameter = 'anu_style_proxy.cache_backend_assets';
        if (!$container->hasParameter($parameter)) {
            $container->setParameter($parameter, $config['process_resources']);
        }
        if ($container->getParameter($parameter)) {
            $loader->load('cache_asset.yml');
        }
    }
}
