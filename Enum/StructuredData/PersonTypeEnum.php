<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Enum\StructuredData;

enum PersonTypeEnum: string
{
    case PERSON = 'Person';
    case SPORTS_ORGANIZATION = 'SportsOrganization';
}