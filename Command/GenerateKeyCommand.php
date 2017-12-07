<?php

namespace NTI\ImpersonationBundle\Command;

use Doctrine\ORM\EntityManager;
use NTI\ImpersonationBundle\Entity\ImpersonationKey;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GenerateKeyCommand extends ContainerAwareCommand
{

    /** @var ContainerInterface $container */
    private $container;

    /** @var EntityManager $em */
    private $em;

    protected function configure()
    {
        $this
            ->setName('nti:impersonation:generate-key')
            ->addArgument('username', null, InputArgument::REQUIRED, 'Username to generate the key for')
            ->setDescription('Generates a key for a given user to be able to perform the impersonation.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument("username");
        $this->getContainer()->get('nti_impersonation.key_generator')->generateKey($username, $output);
    }
}