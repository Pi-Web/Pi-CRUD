<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Enum\StructuredData;

enum EventStatusEnum: string
{
    case CANCELLED = 'EventCancelled';
    case SCHEDULED = 'EventScheduled';
    case MOVED_ONLINE = 'EventMovedOnline';
    case POSTPONED = 'EventPostponed';
    case RESCHEDULED = 'EventRescheduled';
}