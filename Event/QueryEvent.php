<?php

namespace PiWeb\PiCRUD\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

final class QueryEvent extends Event
{
    private ?UserInterface $user;

    private string $type;

    private $queryBuilder;

    public function __construct(?UserInterface $user, string $type, $queryBuilder)
    {
        $this->user = $user;
        $this->type = $type;
        $this->queryBuilder = $queryBuilder;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }
}
