<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;

final class FloatType extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/float.html.twig';

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return FloatType::class;
    }
}