<?php

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

Trait SluggableTrait
{
    /**
     * @var string|null
     *
     * @Gedmo\Slug(fields={"title"}, unique=false)
     * @ORM\Column(length=256)
     */
    protected string $slug = '';

    /**
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}