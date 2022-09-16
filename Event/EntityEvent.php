<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class EntityEvent extends Event
{
    public function __construct(
        private string $type,
        private Object $subject,
        private array $options = []
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getSubject(): object
    {
        return $this->subject;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
