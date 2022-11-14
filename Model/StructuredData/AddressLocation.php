<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class AddressLocation
{
    #[SerializedName('@type')]
    private string $type = 'PostalAddress';
    private string $addressLocality;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getAddressLocality(): string
    {
        return $this->addressLocality;
    }

    public function setAddressLocality(string $addressLocality): void
    {
        $this->addressLocality = $addressLocality;
    }
}
