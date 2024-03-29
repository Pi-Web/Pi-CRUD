<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

Trait SluggableTrait
{
    #[Gedmo\Slug(fields: ['title'], unique: false)]
    #[ORM\Column(type: 'string', length: 256, nullable: true)]
    #[Groups('default')]
    protected string $slug;

    public function getSlug(): string
    {
        return $this->slug;
    }
}
