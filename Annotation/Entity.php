<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Attribute;
use Symfony\Contracts\Service\Attribute\Required;

#[Attribute(Attribute::TARGET_CLASS)]
class Entity
{
    public function __construct(
        #[Required]
        public string $name = '',
        public array $show = [],
        public array $form = [],
        public bool $search = false,
        public array $dashboard = [],
        public array $options = [],
        public ?string $config = null,
        public ?string $structuredDataTransformer = null,
    ) {
    }
}
