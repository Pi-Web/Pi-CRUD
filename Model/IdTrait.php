<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

Trait IdTrait
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("default")
     */
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
