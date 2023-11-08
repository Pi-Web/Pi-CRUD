<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Entity;

use PiWeb\PiCRUD\Model\AuthorTrait;
use PiWeb\PiCRUD\Model\ContentTrait;
use PiWeb\PiCRUD\Model\IdTrait;
use PiWeb\PiCRUD\Model\MetaDescriptionTrait;
use PiWeb\PiCRUD\Model\SluggableTrait;
use PiWeb\PiCRUD\Model\TitleTrait;

abstract class AbstractPiCrudEntity
{
    use IdTrait;
    use TitleTrait;
    use MetaDescriptionTrait;
    use ContentTrait;
    use AuthorTrait;
    use SluggableTrait;

    public function __clone(): void
    {
        $this->id = null;
    }
}
