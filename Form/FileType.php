<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Form;

use PiWeb\PiCRUD\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

final class FileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
            ->add('label', TextType::class, [
                'required' => true,
                'label' => 'Libellé',
                'attr' => [
                    'class' => 'form-control pr-4'
                ],
                'label_attr' => [
                    'class' => 'pr-2'
                ]
            ])
            ->add('fileName', HiddenType::class)
            ->add('documentFile', VichFileType::class, [
                'label' => 'Fichier',
                'required' => false,
                'allow_delete' => true,
                'download_uri' => true,
                'download_label' => 'Télécharger',
                'asset_helper' => true,
                'label_attr' => [
                    'class' => 'pr-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'data_class' => Document::class,
            'class' => null,
        ]);
    }
}
