<?php

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class EntityEvent
 * @package PiWeb\PiCRUD\Event
 */
final class EntityEvent extends Event
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var Object
     */
    private Object $subject;

    /**
     * @var array
     */
    private array $options;

    /**
     * EntityEvent constructor.
     * @param string $type
     * @param $subject
     * @param array $options
     */
    public function __construct(string $type, $subject, $options = [])
    {
        $this->type = $type;
        $this->subject = $subject;
        $this->options = $options;
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
    public function getSubject()
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
