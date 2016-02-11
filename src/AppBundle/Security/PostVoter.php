<?php

namespace AppBundle\Security;

use AppBundle\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    const EDIT = 'edit';

    private $decisionManager;
    private $roleHierarchyChecker;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            // the user must be logged in; if not, deny access
            return false;
        }

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }


        // you know $subject is a Post object, thanks to supports
        /** @var post $post */
        $post = $subject;

        switch($attribute) {
            case self::EDIT:
                return $this->canEdit($post, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }


    private function canEdit(Post $post, UserInterface $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_MANAGER']) &&
            $post->getOwner() == $user
        ) return true;

        return false;
    }
}