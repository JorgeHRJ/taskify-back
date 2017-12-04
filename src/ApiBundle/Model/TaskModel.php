<?php

namespace ApiBundle\Model;

class TaskModel
{
    private $_em;
    private $_container;
    private $_paginator;

    public function __construct( $entityManager, $container, $paginator )
    {
        $this->_em = $entityManager;
        $this->_em->getConnection()->getConfiguration()->setSQLLogger(null);

        $this->_container   = $container;
        $this->_paginator   = $paginator;
    }

    public function create( \ApiBundle\Entity\Task $task, \ApiBundle\Entity\User $user)
    {
        $task->setUser($user);
        
        //set pending status
        $pendingStatus = $this->_em->getRepository('ApiBundle:TaskStatus')->findOneByCodec(\ApiBundle\Entity\TaskStatus::_PENDING_CODE);
        $task->setStatus($pendingStatus);

        $this->_em->persist($task);
        $this->_em->flush();

        return $task;
    }

    public function update( \ApiBundle\Entity\Task $task, \ApiBundle\Entity\TaskStatus $taskStatus = null)
    {
        if( !is_null($taskStatus) )
            $task->setStatus($taskStatus);

        $this->_em->persist($task);
        $this->_em->flush();

        return $task;
    }

    public function getPaginatedTasksByUser(\ApiBundle\Entity\User $user, $page, $pageSize)
    {
        $query      =  $this->_em->getRepository('ApiBundle:Task')->findPaginatedTasksByUser($user, $page);
        
        return  $this->_paginator->paginate($query, $page, $pageSize);
    }

    public function remove(\ApiBundle\Entity\Task $task)
    {
        //soft remove
        $now = new \DateTime('now');
        $task->setDeletedAt($now);

        $this->_em->persist($task);
        $this->_em->flush();
    }

}