<?php

namespace PiWeb\PiCRUD\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PiWeb\PiCRUD\Tools\EntityManager;
use Vich\UploaderBundle\Form\Type\VichImageType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class EntityFormType extends AbstractType
{

    private $configuration;

    private $entityManager;

    public function __construct(array $configuration, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->configuration = $configuration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $properties = $this->entityManager->getEntity($options['type'])['properties'];
        foreach ($properties as $name => $property) {
            switch ($property->type)
            {
                case 'file':
                    $builder->add('file', $property->options['entry_type']);
                    break;
                case 'circuits':
                case 'files':
                    $builder->add($name, CollectionType::class, [
                        'entry_type' => $property->options['entry_type'],
                        'entry_options' => ['label' => false],
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false,
                    ]);
                    break;
                case 'image':
                    $builder->add('imageFile', VichImageType::class, [
                        'required' => false,
                        'allow_delete' => true,
                        'delete_label' => 'Supprimer l\'image',
                        'download_uri' => false,
                        'image_uri' => true,
                        'imagine_pattern' => 'thumbnail',
                        'asset_helper' => true,
                    ]);
                    break;
                case 'ckeditor':
                    $builder->add('content', CKEditorType::class, [
                        'config_name' => 'default'
                    ]);
                    break;
                default:
                    $builder->add($name);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'type' => null,
        ]);
    }
}
