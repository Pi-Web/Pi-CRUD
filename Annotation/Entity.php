<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @Annotation
 * @Target("CLASS")
 */
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
    ) {
    }
}
