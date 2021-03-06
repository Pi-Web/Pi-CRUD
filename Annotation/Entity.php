<?php

namespace PiWeb\PiCRUD\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Entity
 *
 * @Annotation
 * @Target("CLASS")
 */
class Entity
{
    /**
     * @Required
     *
     * @var string
     */
    public string $name = '';

    /**
     * @var array
     */
    public array $show = [];

    /**
     * @var array
     */
    public array $form = [];

    /**
     * @var bool
     */
    public bool $search = false;
}
