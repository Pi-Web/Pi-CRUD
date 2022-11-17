<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DateComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/date.html.twig';

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return DateType::class;
    }
}