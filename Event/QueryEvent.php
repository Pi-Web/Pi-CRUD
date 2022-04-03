<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class QueryEvent
 * @package PiWeb\PiCRUD\Event
 */
final class QueryEvent extends Event
{
    /**
     * QueryEvent constructor.
     * @param UserInterface|null $user
     * @param string $type
     * @param QueryBuilder $queryBuilder
     */
    public function __construct(
        private ?UserInterface $user,
        private string $type,
        private QueryBuilder $queryBuilder
    ) {
    }

    /**
     * @return UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
}
