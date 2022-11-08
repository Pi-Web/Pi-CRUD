<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model;

use Doctrine\ORM\Mapping as ORM;
use PiWeb\PiCRUD\Annotation as PiCRUD;

Trait ContentTrait
{
    #[PiCRUD\Property(
        label: 'Contenu',
        type: 'ckeditor',
        form: ['class' => 'order-1']
    )]
    #[ORM\Column(type: 'text', nullable: true)]
    protected ?string $content = null;

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}
