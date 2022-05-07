<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Entity;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Model\AuthorTrait;
use PiWeb\PiCRUD\Model\ContentTrait;
use PiWeb\PiCRUD\Model\IdTrait;
use PiWeb\PiCRUD\Model\TitleTrait;
use PiWeb\PiCRUD\Annotation as PiCRUD;

/**
 * @PiCRUD\Entity(
 *      name="log",
 * )
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Log
{
    use IdTrait;
    use TitleTrait;
    use ContentTrait;
    use AuthorTrait;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected ?int $code = null;

    /**
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @param int|null $code
     * @return self
     */
    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }
}
