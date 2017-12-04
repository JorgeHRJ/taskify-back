<?php

namespace ApiBundle\Controller;

use ApiBundle\Form\UserType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * @RouteResource("user")
 */
class UserController extends ApiController
{
    /**
     * @Route("/user")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="GET logged user",
     *      description="GET logged user",
     *      section="User"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function getUserAction(Request $request)
    {
        return $this->getUser();
    }

    /**
     * @Route("/user")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Register user",
     *      description="Register user",
     *      section="User"
     * )
     * @View(serializerGroups={"api"})
     * 
     */
    public function postUserAction(Request $request)
    {
        $user = new \ApiBundle\Entity\User();
        $form    = $this->getForm(\ApiBundle\Form\UserType::class, $user, 'POST');
        $form->handleRequest($request);

        //check for existing users with sent data
        $existingUsername = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByUsername($form->get('username')->getData());
        if( isset($existingUsername) )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username exists')), 400 );
        
        $existingUsernameCanonical = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByUsernameCanonical( strtolower($form->get('username')->getData()) );
        if( isset($existingUsernameCanonical) )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username exists')), 400 );
        
        $existingEmail = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByEmail( $form->get('email')->getData() );
        if( isset($existingEmail) )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username email exists')), 400 );

        $existingEmailCanonical = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByEmailCanonical( strtolower($form->get('email')->getData()) );
        if( isset($existingEmailCanonical) )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username email exists')), 400 );

        if ( $form->isValid() ) {
            $newUser = $this->get('api_user_model')->create($user);
            return $newUser;
        }

        return $this->formErrorResponse($form);
    }

    /**
     * @Route("/user")
     * @ApiDoc(
     *      resource=true,
     *      resourceDescription="Register user",
     *      description="Register user",
     *      section="User"
     * )
     * @View(serializerGroups={"api"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function patchUserAction(Request $request)
    {
        $user   = $this->getUser();
        $form   = $this->getForm(\ApiBundle\Form\UserType::class, $user, 'PATCH');
        $form->handleRequest($request);

        //check for existing users with sent data
        $existingUsername = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByUsername($form->get('username')->getData());
            if( isset($existingUsername) && $this->getUser() != $existingUsername )
                return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username exists')), 400 );
          
        $existingUsernameCanonical = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByUsernameCanonical( strtolower($form->get('username')->getData()) );
            if( isset($existingUsernameCanonical) && $this->getUser() != $existingUsernameCanonical )
                return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username exists')), 400 );
                    
        $existingEmail = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByEmail(strtolower( $form->get('email')->getData()) );
        if( isset($existingEmail) && $this->getUser() != $existingEmail )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username email exists')), 400 );

        $existingEmailCanonical = $this->getDoctrineManager()->getRepository('ApiBundle:User')->findOneByEmailCanonical( strtolower($form->get('email')->getData()) );
        if( isset($existingEmailCanonical) && $this->getUser() != $existingEmailCanonical )
            return new JsonResponse( array('code' => 400, 'message' => $this->get('translator')->trans('Username email exists')), 400 );

        if ( $form->isValid() ){
            $updatedUser = $this->get('api_user_model')->update($user);
            return $updatedUser;
        }

        return $this->formErrorResponse($form);
    }

}