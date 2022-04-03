<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class FilterEvent
 * @package PiWeb\PiCRUD\Event
 */
final class FilterEvent extends Event
{
    /**
     * QueryEvent constructor.
     * @param UserInterface|null $user
     * @param string $type
     * @param QueryBuilder $queryBuilder
     * @param Composite $composite
     * @param string $name
     */
    public function __construct(
        private ?UserInterface $user,
        private string $type,
        private QueryBuilder $queryBuilder,
        private Composite $composite,
        private string $name
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

    /**
     * @return Composite
     */
    public function getComposite(): Composite
    {
        return $this->composite;
    }

    /**
     * @param Composite $composite
     */
    public function setComposite(Composite $composite)
    {
        $this->composite = $composite;
    }

    /**
     * @return string $name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}
