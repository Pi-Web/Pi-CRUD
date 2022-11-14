<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Enum\StructuredData;

enum EventAttendanceModeEnum: string
{
    case MIXED = 'MixedEventAttendanceMode';
    case OFFLINE = 'OfflineEventAttendanceMode';
    case ONLINE = 'OnlineEventAttendanceMode';
}