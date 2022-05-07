<?php

namespace PiWeb\PiCRUD\Security;

/**
 * Class DefaultEntityVoter
 * @package PiWeb\PiCRUD\Security
 */
class DefaultEntityVoter extends AbstractVoter
{
    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        if (!(in_array($attribute, [self::ADD, self::LIST, self::ADMIN]))) {
            return false;
        }

        return parent::supports($attribute, $subject);
    }

    /**
     * @param $subject
     * @param $user
     * @return bool
     */
    protected function canAdmin($subject, $user)
    {
        return $this->security->isGranted('ROLE_ADMIN');
    }

    /**
     * @param $entity
     * @param $user
     * @return bool
     */
    protected function canShow($entity, $user)
    {
        return true;
    }

    /**
     * @param $entity
     * @param $user
     * @return bool
     */
    protected function canList($entity, $user)
    {
        return true;
    }

    /**
     * @param $entity
     * @param $user
     * @return bool
     */
    protected function canAdd($entity, $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
     * @param $entity
     * @param $user
     * @return bool
     */
    protected function canEdit($entity, $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
     * @param $map
     * @param $user
     * @return bool
     */
    protected function canDelete($map, $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }
}
