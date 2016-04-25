<?php
namespace UserBundle\Command;

use Doctrine\ORM\EntityManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use UserBundle\Entity\User;
use UserBundle\Repository\UserRepository;


class UserBulkPasswordMailerCommand extends ContainerAwareCommand
{

    /* @var EntityManager */
    private $em;

    /* @var $encoder EncoderFactory */
    private $encoder;

    /* @var $usersRepository UserRepository */
    private $usersRepository;

    private $mailer;

    /* @var $templating TwigEngine */
    private $templating;

    protected function configure()
    {

        $this
            ->setName('user:password:mail')
            ->setDescription('This regenerates all user passwords and mails them');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {

        /* Get all of the services */
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $this->encoder = $this->getContainer()->get('security.encoder_factory');
        $this->mailer = $this->getContainer()->get('mailer');
        $this->templating = $this->getContainer()->get('templating');

        /* Get all of the repositories */
        $this->usersRepository = $this->em->getRepository('UserBundle:User');
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        try {

            $csvUsers = [
                [ "first_name" => "John","last_name" => "Smith", "email_address" => "jsmith@email.com"]
            ];

            foreach($csvUsers as $csvUser) {

                $user = $this->usersRepository->findOneBy(['email' => $csvUser['email_address']]);

                if(!$user instanceof User) {

                    $username = str_replace('.', '', explode('@', $csvUser['email_address'])[0]);

                    $user = new User();
                    $user->setFirstName($csvUser['first_name']);
                    $user->setLastName($csvUser['last_name']);
                    $user->setEmail($csvUser['email_address']);
                    $user->setUsername($username);
                    $user->setIsActive(true);

                }

                $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 10);

                $user->setPassword($this->encodePassword($user, $newPassword));

                $message = \Swift_Message::newInstance()
                    ->setSubject('Afrihost Code Camp :: Password Reminder')
                    ->setFrom('noreply@afrihostcodecamp.com')
                    ->setReplyTo('noreply@afrihostcodecamp.com')
                    ->setSender('noreply@afrihostcodecamp.com')
                    ->setTo($csvUser['email_address'])
                    ->setBody(
                        $this->templating->render(
                            'UserBundle:mails:forgot_password.html.twig',
                            [
                                'first_name' => $user->getFirstName(),
                                'username' => $user->getUsername(),
                                'password' => $newPassword
                            ]
                        ), 'text/html' );
                
                $this->em->persist($user);
                $this->mailer->send($message);

            }

            $this->em->flush();

        } catch (\Exception $e) {

            throw $e;

        }

    }

    protected function encodePassword($user, $plainPassword)
    {

        /* @var $encoder EncoderFactory */
        $encoder = $this->encoder->getEncoder($user);

        return $encoder->encodePassword($plainPassword, $user->getSalt());
    }

}
