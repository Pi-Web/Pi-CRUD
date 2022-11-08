<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class EntityEvent extends Event
{
    public function __construct(
        private readonly string $type,
        private readonly Object $subject,
        private readonly array $options = []
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
