<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;

Trait MetaDescriptionTrait
{
    /**
     * @PiCRUD\Property(
     *      label="Résumé",
     *      form={"class": "order-2"}
     * )
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected ?string $metaDescription = null;

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }
}
