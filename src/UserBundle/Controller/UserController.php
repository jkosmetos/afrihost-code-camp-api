<?php

namespace UserBundle\Controller;

use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use UserBundle\Entity\User;
use WorkshopBundle\Entity\Workshop;
use WorkshopBundle\Repository\WorkshopRepository;

/**
 * User controller.
 *
 * @Route("/api")
 */
class UserController extends Controller
{

    /**
     * @Route("/user/rsvp", name="api_user_rsvp", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function rsvpAction(Request $request)
    {

        $workshopId = $request->get('workshop_id', null);

        /* @var $em EntityManager */
        /* @var $workshopRepo WorkshopRepository */
        $em = $this->getDoctrine()->getManager();

        try {

            $workshop = $em->getReference('WorkshopBundle:Workshop', $workshopId);

            if(!$workshop instanceof Workshop) {
                throw new \Exception('Not a valid Workshop');
            }

            /* @var $user User */
            /* @var $userWorkshop Workshop */
            $user = $this->getUser();
            $userWorkshop = $user->getWorkshop($workshop);

            if($userWorkshop instanceof Workshop) {
                $user->removeWorkshop($workshop);
            } else {
                $user->addWorkshop($workshop);
            }

            $em->persist($user);
            $em->flush($user);

            return ['code' => 1, 'user' => $user, 'workshop' => $user->getWorkshop($workshop)];

        } catch(\Exception $e) {

            return ['code' => 0, 'message' => $e->getMessage()];

        }


    }

    /**
     * @Route("/user/register", name="api_user_register", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {
        $firstName = $request->get('first_name', null);
        $lastName = $request->get('last_name', null);
        $username = $request->get('username', null);
        $emailAddress = $request->get('email_address', null);
        $plainPassword = $request->get('password', null);
        $plainPasswordConfirm = $request->get('password_confirm', null);

        /* @var $em EntityManager */
        /* @var $jwtManager JWTManager */
        $em = $this->getDoctrine()->getManager();
        $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

        try {

            if(empty($firstName)) {
                throw new \Exception('First name cannot be null');
            }

            if(empty($lastName)) {
                throw new \Exception('Last name cannot be null');
            }

            if(empty($username)) {
                throw new \Exception('Username cannot be null');
            }

            if(empty($emailAddress)) {
                throw new \Exception('Email address cannot be null');
            }

            if((empty($plainPassword) || empty($plainPasswordConfirm))) {
                throw new \Exception('Password cannot be null');
            }

            if($plainPassword != $plainPasswordConfirm) {
                throw new \Exception('Passwords do not match');
            }

            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setUsername($username);
            $user->setEmail($emailAddress);
            $user->setPassword($this->encodePassword($user, $plainPassword));

            $em->persist($user);
            $em->flush($user);

            // Authenticate the user and return a token
            $token = $jwtManager->create($user);

            return ['code' => 1, 'user' => $user, 'token' => $token];

        } catch(\Exception $e) {

            return ['code' => 0, 'message' => $e->getMessage()];
        }

    }

    private function encodePassword($user, $plainPassword)
    {

        /* @var $encoder EncoderFactory */
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}
