<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreateTaskStatusCommand extends ContainerAwareCommand
{

    private $manager;

    protected function configure()
    {
        $this
            ->setName('taskify:create:taskstatus')
            ->setDescription('Create initial task statuses')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->manager = $this->getContainer()->get('doctrine')->getManager();
        $this->createTaskStatuses();
    }

    private function createTaskStatuses()
    {
        $counter = 0;

        //pending status
        $newTaskStatus = new \ApiBundle\Entity\TaskStatus();
        $pendingStatus = $this->manager->getRepository('ApiBundle:TaskStatus')->findOneByCodec(\ApiBundle\Entity\TaskStatus::_PENDING_CODE);
        if( !isset($pendingStatus) ) {
            $newTaskStatus->setCodec(\ApiBundle\Entity\TaskStatus::_PENDING_CODE);
            $newTaskStatus->setDescription(\ApiBundle\Entity\TaskStatus::_PENDING_DESC);
            
            $this->manager->persist($newTaskStatus);
            $this->manager->flush();

            $counter++;
        }

        //pending status
        $newTaskStatus = new \ApiBundle\Entity\TaskStatus();
        $pendingStatus = $this->manager->getRepository('ApiBundle:TaskStatus')->findOneByCodec(\ApiBundle\Entity\TaskStatus::_INPROCESS_CODE);
        if( !isset($pendingStatus) ) {
            $newTaskStatus->setCodec(\ApiBundle\Entity\TaskStatus::_INPROCESS_CODE);
            $newTaskStatus->setDescription(\ApiBundle\Entity\TaskStatus::_INPROCESS_DESC);
            
            $this->manager->persist($newTaskStatus);
            $this->manager->flush();

            $counter++;
        }

        //pending status
        $newTaskStatus = new \ApiBundle\Entity\TaskStatus();
        $pendingStatus = $this->manager->getRepository('ApiBundle:TaskStatus')->findOneByCodec(\ApiBundle\Entity\TaskStatus::_COMPLETED_CODE);
        if( !isset($pendingStatus) ) {
            $newTaskStatus->setCodec(\ApiBundle\Entity\TaskStatus::_COMPLETED_CODE);
            $newTaskStatus->setDescription(\ApiBundle\Entity\TaskStatus::_COMPLETED_DESC);
            
            $this->manager->persist($newTaskStatus);
            $this->manager->flush();

            $counter++;
        }

        echo "Added {$counter} new task statuses to database \n";
    }
}