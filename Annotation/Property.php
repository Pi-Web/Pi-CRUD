<?php

namespace PiWeb\PiCRUD\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * Class Property
 *
 * @Annotation
 * @Target("PROPERTY")
 */
class Property
{
    /**
     * @Required
     *
     * @var string
     */
    public string $label;

    /**
     * @var string
     */
    public string $type = 'default';

    /**
     * @var array
     */
    public array $options;

    /**
     * @var array
     */
    public array $admin;

    /**
     * @var array
     */
    public array $form;
}
