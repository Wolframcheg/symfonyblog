<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AdminCreateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:admin_create')
            ->setDescription('Create admin user')
            ->setDefinition(array(
                new InputArgument('username', InputArgument::REQUIRED, 'The username'),
                new InputArgument('email', InputArgument::REQUIRED, 'The email'),
                new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            ));

    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username   = $input->getArgument('username');
        $email      = $input->getArgument('email');
        $password   = $input->getArgument('password');
        $role       = 'ROLE_ADMIN';

        $factory = $this->getContainer()->get('security.encoder_factory');
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $user = $em->getRepository('AppBundle:User')->findBy(['username' => $username]);
        if(!$user)
            $user = new User();
        else $user = $user[0];

        $encoder = $factory->getEncoder($user);
        $pass = $encoder->encodePassword($password, $user->getSalt());
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($pass);
        $user->setRole($role);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf('User <comment>%s</comment> was created/updated', $username));
    }
}
