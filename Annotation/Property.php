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

    public string $type = 'default';

    public $options;

    public $admin;

    public $form;
}
