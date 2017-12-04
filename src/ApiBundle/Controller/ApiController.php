<?php

namespace ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiController extends FOSRestController
{
    
    protected function getDoctrineManager()
    {
        return $this->get('doctrine.orm.default_entity_manager');
    }

    protected function getForm($type, $entity = null, $method = 'POST', $options = array(), $name = '')
    {
        return $this->get('form.factory')->createNamed($name, $type, $entity, $options + array('method' => $method));
    }

    protected function formErrorResponse($form)
    {
        if (!$form->getErrors(true, true)->count()) {
            $form->addError(new FormError('Malformed Form'));
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }

        return $form;
    }

}