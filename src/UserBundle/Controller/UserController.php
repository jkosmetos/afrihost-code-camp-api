<?php

namespace UserBundle\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use UserBundle\Entity\User;

/**
 * User controller.
 *
 * @Route("/api")
 */
class UserController extends Controller
{

    /**
     * @Route("/user/register", name="api_user_register", defaults={"_format"="json"})
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {
        $firstName = $request->get('first_name', null);
        $lastName = $request->get('last_name', null);
        $username = $request->get('username', null);
        $bio = $request->get('bio', '');
        $photo = $request->get('photo', '');
        $emailAddress = $request->get('email_address', null);
        $plainPassword = $request->get('password', null);
        $plainPasswordConfirm = $request->get('password_confirm', null);

        if ($request->isMethod('POST')) {

            /* @var $jwtManager JWTManager */
            $jwtManager = $this->container->get('lexik_jwt_authentication.jwt_manager');

            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();

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
                $user->setBio($bio);
                $user->setPhoto($photo);
                $user->setEmail($emailAddress);
                $user->setPassword($this->encodePassword($user, $plainPassword));

                $em->persist($user);
                $em->flush($user);
                $em->commit();

                // Authenticate the user and return a token
                $token = $jwtManager->create($user);

                return array('user' => $user, 'token' => $token);

            } catch(\Exception $e) {

                $em->getConnection()->rollback();

                return array('code' => 0, 'message' => $e->getMessage());
            }


        }

        return array('code' => 1);
    }

    private function encodePassword($user, $plainPassword)
    {
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}
