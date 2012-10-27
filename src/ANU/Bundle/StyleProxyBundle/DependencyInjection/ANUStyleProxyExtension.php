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

        $container->setParameter('anu_style_proxy.backend_style_server', $config['backend_style_server']);
        $container->setParameter('anu_style_proxy.backend_asset_server', $config['backend_asset_server']);

        if (!isset($config['backend_style_server'])) {
            throw new InvalidArgumentException('Backend style server is required.');
        }
        if (!isset($config['backend_asset_server']) && $config['proxy_mode'] == 'style') {
            throw new InvalidArgumentException('Backend asset server is required.');
        }

        if (in_array($config['proxy_mode'], array('style', 'combined'))) {
            $this->registerStyleServerConfiguration($config, $container, $loader);
        }

        $this->registerAssetServerConfiguration($config, $container, $loader);

        if (in_array($config['proxy_mode'], array('asset', 'combined'))) {
            $this->registerAssetManagerConfiguration($config, $container, $loader);
        }
    }

    private function registerStyleServerConfiguration($config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('style_server.yml');
    }

    private function registerAssetServerConfiguration($config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('asset_server.yml');
    }

    private function registerAssetManagerConfiguration($config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $loader->load('asset_manager.yml');
    }
}
