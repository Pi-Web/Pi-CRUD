<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class TextComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/text.html.twig';

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return TextType::class;
    }
}