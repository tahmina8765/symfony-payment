<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;

class PaymentController extends FOSRestController
{
    public function indexAction()
    {        
        $view = View::create()
            ->setData(array('thread' => 'tahmina'));
        return $this->handleView($view);
    }
    
    public function mobileAction(){
        $view = View::create()
            ->setData(array('thread' => 'tahmina'));
        return $this->handleView($view);
    }
}
