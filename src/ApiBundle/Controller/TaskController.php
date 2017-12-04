<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\TaskType;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\Serializer\SerializationContext;

/**
 * @RouteResource("task")
 */
class TaskController extends ApiController
{
    /**
     * @Route("/tasks")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="GET user tasks",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getAction(Request $request)
    {
        return $this->getUser()->getTasks();
    }

    /**
     * @Route("/tasks/list/{page}")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="GET user tasks with pagination",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getPaginatedTasksAction(Request $request, $page)
    {
        $serializer = $this->get('jms_serializer');
        $pageSize   = 10; //default value, but would be nice to be asked at the request

        $pagination = $this->get('api_task_model')->getPaginatedTasksByUser($this->getUser(), $page, $pageSize);

        $totalItems = $pagination->getTotalItemCount(); 

        return array(
            "items"         => $pagination->getItems(),
            "totalItems"    => $totalItems,
            "pageSize"      => $pageSize,
            "totalPages"    => ceil($totalItems / $pageSize)
        );

    }
    
    /**
     * @Route("/task/{id}")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="GET task by ID",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getTaskAction(Request $request, \ApiBundle\Entity\Task $task)
    {
        return $task;
    }

    /**
     * @Route("/task")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="Create task",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     * 
     */
    public function postAction(Request $request)
    {
        $task = new \ApiBundle\Entity\Task();
        $form = $this->getForm(\ApiBundle\Form\TaskType::class, $task, 'POST');
        $form->handleRequest($request);

        if ( $form->isValid() ) {
            $newTask = $this->get('api_task_model')->create($task, $this->getUser());
            return $newTask;
        }

        return $this->formErrorResponse($form);
    }

    /**
     * @Route("/task/{id}")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="Update task",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function patchAction(Request $request, \ApiBundle\Entity\Task $task)
    {
        $form = $this->getForm(\ApiBundle\Form\TaskType::class, $task, 'PATCH');
        $form->handleRequest($request);

        if ( $form->isValid() ) {
            
            $taskStatus = null;
            if ( !is_null($form->get('status')->getData()) ) {
                $taskStatus = $this->getDoctrineManager()->getRepository('ApiBundle:TaskStatus')->findOneByCodec( $form->get('status')->getData() );
            }
            
            $updatedTask = $this->get('api_task_model')->update($task, $taskStatus);
            return $updatedTask;
        }

        return $this->formErrorResponse($form);
    }

    /**
     * @Route("/search/task")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="Search tasks",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getSearchTaskAction(Request $request)
    {
        $filter = $request->get('filter', null);
        $order  = $request->get('order', 'DESC');
        $search = $request->get('search', null);

        return $this->getDoctrineManager()->getRepository('ApiBundle:Task')->findAndSearchTasks($this->getUser(), $filter, $order, $search);
    }

    /**
     * @Route("/task/remove/{id}")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Task management",
     *      description="Remove a task",
     *      section="Task"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteTaskAction(Request $request, \ApiBundle\Entity\Task $task)
    {
        $this->get('api_task_model')->remove($task);

        return new JsonResponse('OK', 200);
    }

}