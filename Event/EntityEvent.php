<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EntityEvent
 * @package PiWeb\PiCRUD\Event
 */
final class EntityEvent extends Event
{
    /**
     * EntityEvent constructor.
     * @param string $type
     * @param Object $subject
     * @param array $options
     */
    public function __construct(
        private string $type,
        private Object $subject,
        private array $options = []
    ) {
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return Object
     */
    public function getSubject(): object
    {
        return $this->subject;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
