<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use AppBundle\Service\BdmobileService;

class PaymentController extends FOSRestController
{
    public function indexAction()
    {        
        $view = View::create()
            ->setData(array('thread' => 'tahmina'));
        return $this->handleView($view);
    }
    
    public function mobileAction(){
        $mobileService = $this->get('app.bd_mobile');
        $key = $mobileService->subscribe();
        $view = View::create()
            ->setData(array('thread' => $key));
        return $this->handleView($view);
    }
    
}
