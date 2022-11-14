<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class GeoLocation
{
    #[SerializedName('@type')]
    private string $type = 'GeoCoordinates';
    private float $latitude;
    private float $longitude;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
