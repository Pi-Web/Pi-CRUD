<?php

namespace PiWeb\PiCRUD\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

class PiCRUDExtension extends Extension
{
    private $defaultsTemplates = [
        'admin' => '@PiCRUD/admin.html.twig',
        'list' => '@PiCRUD/list.html.twig',
        'field_default' => '@PiCRUD/fields/field_default.html.twig',
        'field_ckeditor' => '@PiCRUD/fields/field_ckeditor.html.twig',
        'field_datetime' => '@PiCRUD/fields/field_datetime.html.twig',
        'field_checkbox' => '@PiCRUD/fields/field_checkbox.html.twig',
        'field_image' => '@PiCRUD/fields/field_image.html.twig',
        'label_default' => '@PiCRUD/fields/label_default.html.twig',
        'label_ckeditor' => '@PiCRUD/fields/label_ckeditor.html.twig',
        'label_datetime' => '@PiCRUD/fields/label_datetime.html.twig',
        'label_checkbox' => '@PiCRUD/fields/label_checkbox.html.twig',
    ];

    private $defaultsEntitiesTemplates = [
        'item_default' => '@PiCRUD/items/item_default.html.twig',
        'item_row' => '@PiCRUD/items/item_row.html.twig',
    ];

    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $this->setDefaults($config);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $definition = $container->getDefinition('PiWeb\PiCRUD\Controller\CRUDController');
        $definition->setArgument('$configuration', $config);

        $definition = $container->getDefinition('PiWeb\PiCRUD\Form\EntityFormType');
        $definition->setArgument('$configuration', $config);

        $definition = $container->getDefinition('block.admin_menu');
        $definition->setArgument('$configuration', $config);

        $definition = $container->getDefinition('block.item');
        $definition->setArgument('$configuration', $config);
    }

    private function setDefaults(array &$config)
    {
        $config['templates'] = array_merge($this->defaultsTemplates, $config['templates']);

        foreach ($config['entities'] as $id => $values) {
            $config['entities'][$id]['templates'] = array_merge($this->defaultsEntitiesTemplates, $values['templates']);
        }
    }
}
