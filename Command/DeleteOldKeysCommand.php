<?php

namespace NTI\ImpersonationBundle\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use NTI\ImpersonationBundle\Entity\ImpersonationKey;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DeleteOldKeysCommand extends ContainerAwareCommand
{

    /** @var ContainerInterface $container */
    private $container;

    /** @var EntityManager $em */
    private $em;

    protected function configure()
    {
        $this
            ->setName('nti:impersonation:delete-old-keys')
            ->setDescription('Delete expired keys.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var EntityRepository $repository */
        $repository = $em->getRepository('NTIImpersonationBundle:ImpersonationKey');
        $qb = $repository->createQueryBuilder('i');
        $qb->andWhere('i.expires <= :now')->setParameter('now', $now);
        $qb->delete();

        $deleted = $qb->getQuery()->execute();

        $output->writeln("Deleted ".$deleted." expired keys.");
    }
}