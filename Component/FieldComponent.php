<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component;

abstract class FieldComponent implements Component
{
    protected array $defaultOptions = [];

    public function __construct(
        public readonly string $name,
        public readonly string $class = '',
        protected readonly ?string $template = null,
        protected array $options = [],
    ) {
        $this->options = array_merge($this->defaultOptions, $this->options);
    }

    abstract public function getFormType(): string;

    public function getFormOptions(): array
    {
        return $this->options;
    }

    public function setFormOptions(array $options): self
    {
        $this->options = array_merge($options, $this->options);

        return $this;
    }
}