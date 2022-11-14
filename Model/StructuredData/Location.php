<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use PiWeb\PiCRUD\Enum\StructuredData\LocationTypeEnum;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class Location
{
    #[SerializedName('@type')]
    private LocationTypeEnum $type;
    private string $name;
    private ?GeoLocation $geo;
    private ?AddressLocation $address;

    public function getType(): string
    {
        return $this->type->value;
    }

    public function setType(LocationTypeEnum $type): self
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

    public function getGeo(): ?GeoLocation
    {
        return $this->geo;
    }

    public function setGeo(?GeoLocation $geo): self
    {
        $this->geo = $geo;

        return $this;
    }

    public function getAddress(): ?AddressLocation
    {
        return $this->address;
    }

    public function setAddress(?AddressLocation $address): self
    {
        $this->address = $address;

        return $this;
    }
}
