<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Convenience wrapper around the role hierarchy voter.
 */
class RoleHierarchyChecker extends RoleHierarchyVoter
{
    /**
     * Check whether a user is granted a specific role, respecting the role hierarchy.
     *
     * @param UserInterface $user
     * @param $requiredRole
     * @return bool Whether this user is granted the $requiredRole
     */
    public function check(UserInterface $user, $requiredRole)
    {
        $roles = array();
        foreach ($user->getRoles() as $roleName) {
            $roles[] = new Role($roleName);
        }
        $token = new AnonymousToken('dummy', 'dummy', $roles);
        return static::ACCESS_GRANTED == $this->vote($token, null, array($requiredRole));
    }
}