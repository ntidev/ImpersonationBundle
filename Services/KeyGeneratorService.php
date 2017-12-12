<?php

namespace NTI\ImpersonationBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use NTI\ImpersonationBundle\Entity\ImpersonationKey;

/**
 * Class KeyGeneratorService
 * @package NTI\ImpersonationBundle\Services
 */
class KeyGeneratorService
{

    /** @var ContainerInterface $container */
    private $container;

    /** @var EntityManagerInterface $em */
    private $em;

    /**
     * SyncService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
    }

    /**
     * Generates a new Impersonation key
     *
     * @param $username
     * @param OutputInterface|null $output
     */
    public function generateKey($username, OutputInterface $output = null) {

        $userClass = $this->container->getParameter('nti_impersonation.user_class');
        $userClassProperty = $this->container->getParameter('nti_impersonation.user_class_property');

        // Look for the user
        $em = $this->container->get('doctrine')->getManager();

        $user = $em->getRepository($userClass)->findOneBy(array($userClassProperty => $username));

        if(!$user) {
            if($output) {
                $output->writeln("Error: The User was not found.");
            }
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