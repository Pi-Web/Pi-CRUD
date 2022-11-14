<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use PiWeb\PiCRUD\Enum\StructuredData\PersonTypeEnum;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class Person
{
    #[SerializedName('@type')]
    private PersonTypeEnum $type;
    private string $name;

    public function getType(): string
    {
        return $this->type->value;
    }

    public function setType(PersonTypeEnum $type): self
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
}
