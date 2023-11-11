<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

final class QueryEvent extends Event
{
    public function __construct(
        private readonly ?UserInterface $user,
        private readonly string         $type,
        private QueryBuilder            $queryBuilder
    ) {
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder): void
    {
        $this->queryBuilder = $queryBuilder;
    }
}
