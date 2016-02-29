<?php

namespace UserBundle\Repository;

use UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    public function findOneByUsernameOrEmail($username)
    {

        $dql = 'SELECT user
                FROM UserBundle:User user
                WHERE
                user.email = :email OR user.username = :username';

        $parameters = array('username' => $username, 'email' => $username);

        $em = $this->getEntityManager();
        $query = $em->createQuery($dql)->setParameters($parameters);

        return $query->getOneOrNullResult();
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findOneByUsernameOrEmail($username);

        if (!$user) {
            throw new UsernameNotFoundException('No user found for username '.$username);
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }

        return $this->find($user->getId());
    }

    public function supportsClass($class)
    {
        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

}
