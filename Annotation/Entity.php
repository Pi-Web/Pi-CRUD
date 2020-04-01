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
    public $name;

    public $show;

    public $form;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
