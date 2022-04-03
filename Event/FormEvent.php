<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class FormEvent
 * @package PiWeb\PiCRUD\Event
 */
final class FormEvent extends Event
{
    /**
     * FormEvent constructor.
     * @param string $field
     * @param $properties
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function __construct(
        private string $field,
        private $properties,
        private FormBuilderInterface $builder,
        private array $options
    ) {
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getProperties(): mixed
    {
        return $this->properties;
    }

    /**
     * @return FormBuilderInterface
     */
    public function getBuilder(): FormBuilderInterface
    {
        return $this->builder;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
