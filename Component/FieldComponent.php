<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component;

abstract class FieldComponent implements Component
{
    protected array $defaultFormOptions = [];

    public function __construct(
        protected string $name = '',
        protected string $class = '',
        protected readonly ?string $template = null,
        protected array $options = [],
        protected array $formOptions = [],
    ) {
        $this->formOptions = array_merge($this->defaultFormOptions, $this->formOptions);
    }

    abstract public function getFormType(): string;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): self
    {
        $this->options = array_merge($options, $this->options);

        return $this;
    }

    public function getFormOptions(): array
    {
        return $this->formOptions;
    }

    public function setFormOptions(array $formOptions): self
    {
        $this->formOptions = array_merge($formOptions, $this->formOptions);

        return $this;
    }
}