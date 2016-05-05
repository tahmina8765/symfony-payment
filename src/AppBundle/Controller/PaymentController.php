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
    
    
    /**
     * This is the documentation description of your method, it will appear
     * on a specific pane. It will read all the text until the first
     * annotation.
     *
     * @ApiDoc(
     *  resource=true,
     *  description="This is a description of your API method",
     *  filters={
     *      {"name"="a-filter", "dataType"="integer"},
     *      {"name"="another-filter", "dataType"="string", "pattern"="(foo|bar) ASC|DESC"}
     *  }
     * )
     */
    public function statusAction(){
        $mobileService = $this->get('app.bd_mobile');
        $key = $mobileService->subscribe();
        $view = View::create()
            ->setData(array('thread' => $key));
        return $this->handleView($view);
    }
    
}
