<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Factory;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use PiWeb\PiCRUD\Component\Component;
use PiWeb\PiCRUD\Component\Field\CheckboxComponent;
use PiWeb\PiCRUD\Component\Field\FloatType;
use PiWeb\PiCRUD\Component\Field\TextComponent;

final class ComponentFactory
{
    public function get(string $attributeClass, array $attributeArguments): ?Component
    {
        return match ($attributeClass) {
            Column::class => $this->getFromOrmColumnAttribute($attributeArguments),
            default => null,
        };
    }

    public function getFromOrmColumnAttribute(array $attributeArguments): ?Component
    {
        return match ($attributeArguments['type']) {
            Types::STRING => new TextComponent(
                name: $attributeArguments['name'],
            ),
            Types::BOOLEAN => new CheckboxComponent(
                name: $attributeArguments['name'],
            ),
            Types::DECIMAL => new FloatType(
                name: $attributeArguments['name'],
            ),
            default => null,
        };
    }
}
