<?php

namespace AppBundle\Security;

use AppBundle\Entity\Comment;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    const EDIT = 'edit';

    private $decisionManager;
    private $roleHierarchyChecker;

    public function __construct(AccessDecisionManagerInterface $decisionManager, RoleHierarchyChecker $roleHierarchyChecker)
    {
        $this->decisionManager = $decisionManager;
        $this->roleHierarchyChecker = $roleHierarchyChecker;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Comment) {
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
        /** @var Comment $comment */
        $comment = $subject;

        switch($attribute) {
            case self::EDIT:
                return $this->canEdit($comment, $user, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }


    private function canEdit(Comment $comment, UserInterface $user, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_MANAGER']) &&
            ( !$comment->getUser() || !$this->roleHierarchyChecker->check($comment->getUser(), 'ROLE_ADMIN') ) &&
            $comment->getPost()->getOwner() == $user
        ) return true;


        if($comment->getUser() === $user)
            return true;

        return false;
    }
}