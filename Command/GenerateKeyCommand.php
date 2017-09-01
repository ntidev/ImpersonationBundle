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

        // Look for the user
        $em = $this->getContainer()->get('doctrine')->getManager();

        # Todo: Configurable, both the repository and the fieldname, and email
        $user = $em->getRepository('AppBundle:User\User')->findOneBy(array("username" => $username));

        if(!$user) {
            $output->writeln("Error: The user was not found.");
            return;
        }

        // Look for other keys for that user
        $previousKeys = $em->getRepository('NTIImpersonationBundle:ImpersonationKey')->findBy(array("username" => $username));
        foreach($previousKeys as $previousKey) {
            $em->remove($previousKey);
        }

        // Prepare the key
        $key = strtoupper(uniqid(time()).md5($username));
        $expires = new \DateTime();

        # Todo: Configurable
        $expires->add(new \DateInterval("PT5M"));
        $impersonationKey = new ImpersonationKey();
        $impersonationKey->setExpires($expires);
        $impersonationKey->setKey($key);
        $impersonationKey->setUsername($username);

        $em->persist($impersonationKey);
        try {
            $output->writeln("Key: " . $key . " Expires: " . $expires->format('m/d/Y h:i:s A') . " (".$expires->getTimezone()->getName().")");
            $em->flush();
        } catch (\Exception $ex) {
            $output->writeln("An error occurred while generating a key: ". $ex->getMessage());
        }
    }
}