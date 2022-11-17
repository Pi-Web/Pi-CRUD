<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component\Field;

use PiWeb\PiCRUD\Component\FieldComponent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

final class EntityComponent extends FieldComponent
{
    private const DEFAULT_TEMPLATE = '@PiCRUD/component/entity.html.twig';

    public function getTemplate(): string
    {
        return $this->template ?? self::DEFAULT_TEMPLATE;
    }

    public function getFormType(): string
    {
        return EntityType::class;
    }
}