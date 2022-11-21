<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Form\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

final class FilesComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/files.html.twig';

    protected array $defaultFormOptions = [
        'entry_type' => FileType::class,
        'entry_options' => ['label' => false],
        'allow_add' => true,
        'allow_delete' => true,
        'by_reference' => false,
    ];

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return CollectionType::class;
    }
}