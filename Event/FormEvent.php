<?php

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
     * @var string
     */
    private string $field;

    /**
     * @var
     */
    private $properties;

    /**
     * @var FormBuilderInterface
     */
    private FormBuilderInterface $builder;

    /**
     * @var array
     */
    private array $options;

    /**
     * FormEvent constructor.
     * @param string $field
     * @param $properties
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function __construct(string $field, $properties, FormBuilderInterface $builder, array $options)
    {
        $this->field = $field;
        $this->properties = $properties;
        $this->builder = $builder;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return FormBuilderInterface
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }
}
