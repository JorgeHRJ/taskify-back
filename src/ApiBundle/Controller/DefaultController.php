<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * @RouteResource("default")
 */
class DefaultController extends ApiController
{
    /**
     * @Route("/default/example")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="GET request example",
     *      description="GET request example",
     *      section="Default"
     * )
     * @View(serializerGroups={"api"})
     */
    public function getExampleAction()
    {
        return new JsonResponse('Hello world!', 200);
    }

    /**
     * @Route("/default/example")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="POST request example with auth",
     *      description="POST request example with auth",
     *      section="Default"
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function postExampleAction()
    {
        return new JsonResponse('Hello world!', 200);
    }

    /**
     * @Route("/default/user/{id}")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Get a user by ID",
     *      description="Get a user by ID",
     *      section="Default"
     * )
     * @Security("is_granted('ROLE_ADMIN')")
     * @View(serializerGroups={"api"})
     * 
     */
    public function getUserAction(Request $request, \ApiBundle\Entity\User $user)
    {
        return $user;
    }
}
