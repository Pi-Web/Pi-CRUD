<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    public function __construct(
        public ?string $config = null,
    ) {
    }
}
