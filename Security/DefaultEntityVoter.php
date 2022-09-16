<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Security;

class DefaultEntityVoter extends AbstractVoter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!(in_array($attribute, [self::ADD, self::LIST, self::ADMIN]))) {
            return false;
        }

        return parent::supports($attribute, $subject);
    }

    protected function canAdmin($subject, $user): bool
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    protected function canShow($entity, $user): bool
    {
        return true;
    }

    protected function canList($entity, $user): bool
    {
        return true;
    }

    protected function canAdd($entity, $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    protected function canEdit($entity, $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    protected function canDelete($map, $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
