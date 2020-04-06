<?php

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Form\FormBuilderInterface;

final class EntityEvent extends Event
{
    private string $type;

    private Object $subject;

    private array $options;

    public function __construct(string $type, $subject, $options = [])
    {
        $this->type = $type;
        $this->subject = $subject;
        $this->options = $options;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
