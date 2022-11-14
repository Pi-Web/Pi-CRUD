<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use PiWeb\PiCRUD\Enum\StructuredData\OrganizerTypeEnum;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class Organizer
{
    #[SerializedName('@type')]
    private OrganizerTypeEnum $type;
    private string $name;
    private string $url;

    public function getType(): string
    {
        return $this->type->value;
    }

    public function setType(OrganizerTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
