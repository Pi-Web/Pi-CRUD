<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

final class FormEvent extends Event
{
    public function __construct(
        private readonly string $field,
        private $properties,
        private readonly FormBuilderInterface $formBuilder,
        private readonly array $options
    ) {
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getProperties(): mixed
    {
        return $this->properties;
    }

    public function getBuilder(): FormBuilderInterface
    {
        return $this->formBuilder;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
