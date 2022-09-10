<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class QueryResultEvent extends Event
{
    public function __construct(
        private string $type,
        private array|object $results,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getResults(): array|object
    {
        return $this->results;
    }

    public function setResults(array|object $results): self
    {
        $this->results = $results;

        return $this;
    }
}
