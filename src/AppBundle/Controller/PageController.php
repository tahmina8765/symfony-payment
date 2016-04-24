<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;


class PageController extends FOSRestController
{
    /**
     * @var ViewHandler
     */
    protected $viewHandler;
    
    public function __construct(ViewHandler $viewHandler)
    {
        $this->viewHandler = $viewHandler;
    }
    
    public function getPageAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Page')->find($id);
        
        $view = $this->view($data, 200)
            // ->setTemplate("MyBundle:Users:getUsers.html.twig")
            // ->setTemplateVar('users')
        ;

        return $this->viewHandler->handle($view);

//        return $this->view($entities);
    }

}
