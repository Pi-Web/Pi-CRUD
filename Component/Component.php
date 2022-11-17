<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Component;

interface Component
{
    /**
     * Get the template of this component used to render it.
     *
     * @return string The template location.
     */
    public function getTemplate(): string;
}