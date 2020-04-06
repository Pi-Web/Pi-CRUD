<?php

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

final class FormEvent extends Event
{
    private string $field;

    private $properties;

    private FormBuilderInterface $builder;

    private array $options;

    public function __construct(string $field, $properties, FormBuilderInterface $builder, array $options)
    {
        $this->field = $field;
        $this->properties = $properties;
        $this->builder = $builder;
        $this->options = $options;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getBuilder()
    {
        return $this->builder;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
