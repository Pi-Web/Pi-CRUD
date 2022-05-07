<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;

Trait MetaDescriptionTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @PiCRUD\Property(
     *      label="Résumé",
     *      form={"class": "order-2"}
     * )
     */
    protected ?string $metaDescription = null;

    /**
     * @return string|null
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string|null $title
     * @return $this
     */
    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}
