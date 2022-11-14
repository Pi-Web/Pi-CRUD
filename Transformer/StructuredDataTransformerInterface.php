<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Transformer;


use PiWeb\PiCRUD\Model\StructuredData\AbstractStructuredData;

interface StructuredDataTransformerInterface
{
    public function transform($object): AbstractStructuredData;
}
