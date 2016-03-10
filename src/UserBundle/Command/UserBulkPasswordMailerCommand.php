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
                [ "first_name" => "Sarel", "last_name" => "Van Der Walt","email_address" => "sarel@afrihost.com"],
                [ "first_name" => "Sacheen", "last_name" => "Dhanjie","email_address" => "sacheend@afrihost.com"],
                [ "first_name" => "Gavin", "last_name" => "McLeland","email_address" => "gavinm@afrihost.com"],
                [ "first_name" => "Lee", "last_name" => "Pelser","email_address" => "lee@afrihost.com"],
                [ "first_name" => "John", "last_name" => "Kosmetos","email_address" => "johnk@afrihost.com"],
                [ "first_name" => "Johnathan", "last_name" => "Dell","email_address" => "johnathandell@afrihost.com"],
                [ "first_name" => "Dale", "last_name" => "Attree","email_address" => "dalea@afrihost.com"],
                [ "first_name" => "Brad", "last_name" => "Mostert","email_address" => "bradm@afrihost.com"],
                [ "first_name" => "Michael","last_name" => "Magumise","email_address" => "kmagumise@hotmail.com"],
                [ "first_name" => "Lear","last_name" => "Pather","email_address" => "lear.p@afrihost.com"],
                [ "first_name" => "Walter","last_name" => "Da Silva","email_address" => "walter@afrihost.com"],
                [ "first_name" => "Ryan","last_name" => "Lumsden","email_address" => "ryan@afrihost.com"],
                [ "first_name" => "Jonathan","last_name" => "Potgieter","email_address" => "jonnypotgieter@gmail.com"],
                [ "first_name" => "Jailosi","last_name" => "Phiri","email_address" => "jailosi.p@afrihost.com"],
                [ "first_name" => "James","last_name" => "Mohono","email_address" => "james.mohono@afrihost.com"],
                [ "first_name" => "Fortune","last_name" => "Maseko","email_address" => "fortune.m@afrihost.com"],
                [ "first_name" => "Mawande","last_name" => "Nxumalo","email_address" => "mawande.n@afrihost.com"],
                [ "first_name" => "Nkululeko","last_name" => "Dube","email_address" => "mrpeaced@gmail.com"],
                [ "first_name" => "Kendall","last_name" => "Jordaan","email_address" => "kjordaan405@gmail.com"],
                [ "first_name" => "Sanushen","last_name" => "Govender","email_address" => "sanushen.g@afrihost.com"],
                [ "first_name" => "Dowayne","last_name" => "Breedt","email_address" => "dowayne.b@afrihost.com"],
                [ "first_name" => "Kudzaishe","last_name" => "Chitonga","email_address" => "kudzaishec@afrihost.com"],
                [ "first_name" => "Thabo","last_name" => "Nkgweng","email_address" => "thabo.n@afrihost.com"],
                [ "first_name" => "Thandaninkosi","last_name" => "Moyo","email_address" => "thandaninkosi.m@afrihost.com"],
                [ "first_name" => "Sizwe","last_name" => "Mkhonto","email_address" => "sizwe.m@afrihost.com"],
                [ "first_name" => "Matthew","last_name" => "Ketley","email_address" => "matthew.k@afrihost.com"],
                [ "first_name" => "Sabelo","last_name" => "Mbokazi","email_address" => "sabelom@afrihost.com"],
                [ "first_name" => "Koketso","last_name" => "Polorie","email_address" => "kndpolorie@gmail.com"],
                [ "first_name" => "Larry","last_name" => "Nxumalo","email_address" => "tlnxumalo@gmail.com"],
                [ "first_name" => "Asaph","last_name" => "Mulaisi","email_address" => "asaph.m@afrihost.com"],
                [ "first_name" => "Edward","last_name" => "Stroebel","email_address" => "edwards@afrihost.com"],
                [ "first_name" => "Ruan","last_name" => "Prinsloo","email_address" => "ruan.prinsloo@afrihost.com"]
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
