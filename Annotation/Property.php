<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
class Property
{
    public function __construct(
        #[Required]
        public string $label = '',
        public string $type = 'default',
        public array $options = [],
        public array $admin = [],
        public array $form = [],
        public array $search = [],
    ) {
    }
}
