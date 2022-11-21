<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Factory;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Component\Component;
use PiWeb\PiCRUD\Component\Field\CheckboxComponent;
use PiWeb\PiCRUD\Component\Field\CkEditorComponent;
use PiWeb\PiCRUD\Component\Field\DateTimeComponent;
use PiWeb\PiCRUD\Component\Field\FloatComponant;
use PiWeb\PiCRUD\Component\Field\IntegerComponent;
use PiWeb\PiCRUD\Component\Field\TextComponent;
use PiWeb\PiCRUD\Component\FieldComponent;
use PiWeb\PiCRUD\Exception\ComponentException;

final class ComponentFactory
{
    /**
     * @throws ComponentException
     */
    public function get(string $propertyName, array $componentConfiguration): FieldComponent
    {
        $type = $componentConfiguration['options']['type'] ?? 'undefined';

        $componentClass = match ($type) {
            Types::STRING => TextComponent::class,
            Types::TEXT => CkEditorComponent::class,
            Types::BOOLEAN => CheckboxComponent::class,
            Types::INTEGER => IntegerComponent::class,
            Types::DECIMAL => FloatComponant::class,
            Types::DATETIME_MUTABLE => DateTimeComponent::class,
            default => throw new ComponentException(sprintf(
                'Unable to generate component for property %s with type %s',
                $propertyName,
                $type
            )),
        };

        return new $componentClass(
            name: $propertyName,
        );
    }

    /**
     * This method allow to configure a component with attributes information declare on entity property.
     * @throws ComponentException
     */
    public function configure(string $propertyName, ?FieldComponent $component, array $attributes): Component
    {
        $componentConfiguration = $this->extractAttributes($attributes);

        if (null === $component) {
            $component = $this->get($propertyName, $componentConfiguration);
        }

        $component
            ->setName($propertyName)
            ->setOptions($componentConfiguration['options'])
            ->setFormOptions($componentConfiguration['formOptions']);

        return $component;
    }

    private function extractAttributes(array $attributes): array
    {
        $options = $formOptions = [];

        if (isset($attributes[ORM\Column::class])) {
            $ormAttribute = $attributes[ORM\Column::class];

            $options['type'] = $ormAttribute['type'];
            $formOptions['required'] = empty($ormAttribute['nullable']);
            $formOptions['scale'] = $ormAttribute['scale'] ?? null;
        }

        if (isset($attributes[ORM\ManyToOne::class])) {
            $formOptions['class'] = $attributes[ORM\ManyToOne::class]['targetEntity'];
        }

        return [
            'options' => array_filter($options),
            'formOptions' => array_filter($formOptions),
        ];
    }
}
