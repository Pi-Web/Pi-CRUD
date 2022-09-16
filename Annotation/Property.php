<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
class Property
{
    /**
     * @Required
     */
    public string $label = '';
    public string $type = 'default';
    public array $options = [];
    public array $admin = [];
    public array $form = [];
    public array $search = [];
}
