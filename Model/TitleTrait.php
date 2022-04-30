<?php

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;
use Symfony\Component\Serializer\Annotation\Groups;

Trait TitleTrait
{
    /**
     * @ORM\Column(type="string", length=255)
     * @PiCRUD\Property(
     *      label="Titre",
     *      admin={"class": "font-weight-bold"},
     *      form={"class": "order-1"}
     * )
     * @Groups("default")
     */
    protected string $title = '';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getTitle();
    }
}
