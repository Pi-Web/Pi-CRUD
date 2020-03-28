<?php

namespace Owp\OwpCrudAdmin\Event;

use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

final class AccessEvent extends Event
{
    private UserInterface $user;

    private string $type;

    private string $mode;

    private array $configuration;

    public function __construct(UserInterface $user, string $type, string $mode, array $configuration)
    {
        $this->user = $user;
        $this->type = $type;
        $this->mode = $mode;
        $this->configuration = $configuration;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function getConfiguration(): ?array
    {
        return $this->configuration;
    }
}
