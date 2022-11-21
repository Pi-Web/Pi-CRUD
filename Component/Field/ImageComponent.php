<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class ImageComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/image.html.twig';

    protected array $defaultFormOptions = [
        'label' => 'Image',
        'required' => false,
        'allow_delete' => true,
        'delete_label' => 'Supprimer l\'image',
        'download_uri' => false,
        'image_uri' => true,
        'imagine_pattern' => 'thumbnail',
        'asset_helper' => true,
    ];

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return VichImageType::class;
    }
}