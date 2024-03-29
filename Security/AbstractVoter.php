<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

abstract class AbstractVoter extends Voter
{
    final const SHOW = 'show';
    final const LIST = 'list';
    final const ADMIN = 'admin';
    final const ADD = 'add';
    final const EDIT = 'edit';
    final const DELETE = 'delete';

    public function __construct(
        protected readonly Security $security,
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
