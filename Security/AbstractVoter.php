<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

/**
 * Class AbstractVoter
 * @package App\Security
 */
abstract class AbstractVoter extends Voter
{
    const SHOW = 'show';
    const LIST = 'list';
    const ADMIN = 'admin';
    const ADD = 'add';
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * AbstractVoter constructor.
     * @param Security $security
     */
    public function __construct(
        protected Security $security
    ) {
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject)
    {
        return method_exists($this, 'can' . ucfirst($attribute));
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        return $this->{'can' . ucfirst($attribute)}($subject, $user);
    }
}
