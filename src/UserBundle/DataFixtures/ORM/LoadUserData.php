<?php
namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $encoder = $this->container->get('security.password_encoder');

        $user = new User();
        $user->setFirstName('John');
        $user->setLastName('Kosmetos');
        $user->setEmail('johnk@afrihost.com');
        $user->setUsername('johnk');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'johnk1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Sacheen');
        $user->setLastName('Dhanjie');
        $user->setEmail('sacheend@afrihost.com');
        $user->setUsername('sacheend');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'sacheend1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Johnathan');
        $user->setLastName('Dell');
        $user->setEmail('johnathandell@afrihost.com');
        $user->setUsername('johnathandell');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'johnathandell1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Sarel');
        $user->setLastName('Van Der Walt');
        $user->setEmail('sarel@afrihost.com');
        $user->setUsername('sarel');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'sarel1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Lee');
        $user->setLastName('Pelser');
        $user->setEmail('lee@afrihost.com');
        $user->setUsername('lee');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'lee1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Brad');
        $user->setLastName('Mostert');
        $user->setEmail('bradm@afrihost.com');
        $user->setUsername('bradm');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'bradm1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Dale');
        $user->setLastName('Attree');
        $user->setEmail('dalea@afrihost.com');
        $user->setUsername('dalea');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'dalea1234'));

        $manager->persist($user);

        $user = new User();
        $user->setFirstName('Gavin');
        $user->setLastName('McLeland');
        $user->setEmail('gavin@afrihost.com');
        $user->setUsername('gavin');
        $user->setRoles(array('ROLE_LECTURER'));
        $user->setPassword($encoder->encodePassword($user, 'gavin1234'));

        $manager->persist($user);


        $manager->flush();

    }
}
