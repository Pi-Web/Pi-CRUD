<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use DateTime;
use PiWeb\PiCRUD\Enum\StructuredData\EventAttendanceModeEnum;
use PiWeb\PiCRUD\Enum\StructuredData\EventStatusEnum;
use PiWeb\PiCRUD\Enum\StructuredData\TypeEnum;

final class SportsEvent extends AbstractStructuredData
{
    private string $name;
    private EventStatusEnum $eventStatus;
    private EventAttendanceModeEnum $eventAttendanceMode;
    private ?DateTime $startDate;
    private ?Organizer $organizer;
    private ?DateTime $endDate;
    private ?Location $location;

    public function __construct()
    {
        $this->type = TypeEnum::SPORTS_EVENT;
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

    public function getEventStatus(): string
    {
        return $this->eventStatus->value;
    }

    public function setEventStatus(EventStatusEnum $eventStatus): self
    {
        $this->eventStatus = $eventStatus;

        return $this;
    }

    public function getEventAttendanceMode(): string
    {
        return $this->eventAttendanceMode->value;
    }

    public function setEventAttendanceMode(EventAttendanceModeEnum $eventAttendanceMode): self
    {
        $this->eventAttendanceMode = $eventAttendanceMode;

        return $this;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getOrganizer(): ?Organizer
    {
        return $this->organizer;
    }

    public function setOrganizer(?Organizer $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
