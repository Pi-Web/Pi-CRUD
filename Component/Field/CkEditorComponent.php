<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use PiWeb\PiCRUD\Component\FieldComponent;

final class CkEditorComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/ckeditor.html.twig';

    protected array $defaultFormOptions = [
        'config_name' => 'default'
    ];

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return CKEditorType::class;
    }
}