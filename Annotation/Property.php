<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Attribute;
use Symfony\Contracts\Service\Attribute\Required;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
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
