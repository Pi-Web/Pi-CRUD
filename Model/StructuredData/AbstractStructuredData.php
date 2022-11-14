<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use PiWeb\PiCRUD\Enum\StructuredData\TypeEnum;
use Symfony\Component\Serializer\Annotation\SerializedName;

abstract class AbstractStructuredData
{
    #[SerializedName('@context')]
    protected string $context = 'https://schema.org';

    #[SerializedName('@type')]
    protected TypeEnum $type;

    protected string $url;

    protected string $description;
    
    public function getContext(): string
    {
        return $this->context;
    }
    
    public function setContext(string $context): self
    {
        $this->context = $context;
        
        return $this;
    }

    public function getType(): string
    {
        return $this->type->value;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
