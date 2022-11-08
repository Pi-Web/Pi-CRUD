<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Event;

use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

final class FilterEvent extends Event
{
    public function __construct(
        private readonly ?UserInterface $user,
        private readonly string         $type,
        private QueryBuilder            $queryBuilder,
        private Composite               $composite,
        private string                  $name
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

    public function setQueryBuilder(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    public function getComposite(): Composite
    {
        return $this->composite;
    }

    public function setComposite(Composite $composite)
    {
        $this->composite = $composite;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
}
