<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Entity;

use PiWeb\PiCRUD\Model\AuthorTrait;
use PiWeb\PiCRUD\Model\ContentTrait;
use PiWeb\PiCRUD\Model\IdTrait;
use PiWeb\PiCRUD\Model\SluggableTrait;
use PiWeb\PiCRUD\Model\TitleTrait;

/**
 * Class PiCrudEntity
 * @package PiWeb\PiCRUD\Entity
 */
abstract class AbstractPiCrudEntity
{
    use IdTrait;
    use TitleTrait;
    use ContentTrait;
    use AuthorTrait;
    use SluggableTrait;
}
