<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Form\FileType;

final class FileComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/file.html.twig';

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return FileType::class;
    }
}