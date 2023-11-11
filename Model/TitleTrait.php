<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;
use Symfony\Component\Serializer\Annotation\Groups;

Trait TitleTrait
{
    /**
     * @PiCRUD\Property(
     *      label="Titre",
     *      admin={"class": "font-weight-bold"},
     *      form={"class": "order-1"}
     * )
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('default')]
    protected string $title = '';

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
