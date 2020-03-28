<?php

namespace PiWeb\PiCRUD\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class PiCRUDExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        // you now have these 2 config keys
        // $config['twitter']['client_id'] and $config['twitter']['client_secret']
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $definition = $container->getDefinition('PiWeb\PiCRUD\Controller\CRUDController');
        $definition->setArgument('$configuration', $config);
    }
}
