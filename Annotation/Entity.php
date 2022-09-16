<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class Entity
{
    /**
     * @Required
     */
    public string $name = '';
    public array $show = [];
    public array $form = [];
    public bool $search = false;
    public array $dashboard = [];
    public array $options = [];
}
