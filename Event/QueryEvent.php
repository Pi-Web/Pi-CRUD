<?php

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
     * @var UserInterface|null
     */
    private ?UserInterface $user;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $queryBuilder;

    /**
     * QueryEvent constructor.
     * @param UserInterface|null $user
     * @param string $type
     * @param $queryBuilder
     */
    public function __construct(?UserInterface $user, string $type, QueryBuilder $queryBuilder)
    {
        $this->user = $user;
        $this->type = $type;
        $this->queryBuilder = $queryBuilder;
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
