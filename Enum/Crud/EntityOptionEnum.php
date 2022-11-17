<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Enum\Crud;

enum EntityOptionEnum: string
{
    case PAGINATION = 'pagination';
    case TRANSFORMER_STRUCTURED_DATA = 'transformer.structured_data';
}