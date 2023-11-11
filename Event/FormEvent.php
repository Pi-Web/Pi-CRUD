<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

final class FormEvent extends Event
{
    public function __construct(
        private readonly string               $field,
        private readonly mixed                $properties,
        private readonly FormBuilderInterface $builder,
        private readonly array                $options
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
        return $this->builder;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
