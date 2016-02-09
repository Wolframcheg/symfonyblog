<?php
namespace AppBundle\Services;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminUserProvider implements UserProviderInterface {

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em  = $em;
    }

    public function loadUserByUsername($username)
    {
        // Do we have a local record?
        if ($user = $this->findUserByNameAndRole($username, User::ROLE_ADMIN )) {
            return $user;
        }

        throw new UsernameNotFoundException(sprintf('No record found for user %s', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'AppBundle\Entity\User';
    }

    protected function findUserByNameAndRole($username, $role)
    {

        $repository = $this->em->getRepository('AppBundle:User');
        return $repository->findUserByNameAndRole($username, $role);
    }
}