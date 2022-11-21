<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

final class EntityVoter
{
    final const SHOW = 'show';
    final const LIST = 'list';
    final const ADMIN = 'admin';
    final const ADD = 'add';
    final const EDIT = 'edit';
    final const DELETE = 'delete';

    public function __construct(
        protected readonly Security $security,
        protected RequestStack $requestStack,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return method_exists($this, 'can' . ucfirst($attribute));
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $this->{'can' . ucfirst($attribute)}($subject, $user);
    }
}
