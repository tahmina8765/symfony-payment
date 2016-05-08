<?php

namespace PaymentBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class BongoRestController extends FOSRestController
{

    public function formateResponse($data = null, $status = 400, $message = null)
    {

        $returnData = array(
            'status' => $status,
        );

        if (!empty($data)) {
            $returnData['data'] = $data;
        }
        
        if (!empty($message)) {
            $returnData['message'] = $message;
        }
        return $returnData;
    }

}
