<?php

namespace PaymentBundle\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use PaymentBundle\Form\InvoiceType;
use PaymentBundle\Entity\Invoice;
use PaymentBundle\Exception\InvalidFormException;

class PaymentService
{

    private $invoiceService;
    private $BDTelcoService;

    public function __construct($invoiceService, $BDTelcoService)
    {
        $this->invoiceService = $invoiceService;
        $this->BDTelcoService = $BDTelcoService;
    }
    
    public function processPayment(Invoice $invoice){
        $getway = $invoice->getGateway();
        $invoice->setPhone('8801721965232');
        switch($getway){
            case 'BD-TELCO':
                $response = $this->BDTelcoService->payment($invoice->getPhone(), '');
                if(200 == $response->getStatusCode()){
                    $body = json_decode($response->getBody()->getContents(), true);
                    if($body['success']){
                        return true;
                    }else{
                        return false;
                    }
                }else{
                    // TO DO
                };
                break;
        }
        die();
//        http://103.17.180.103:8088/JsonChannel/subscriptioncheck.aspx?apikey=DCDZMYYXFZ7Q3MW0WTXLE9M7RITNDO6N3U7DO9U88I4ZS9MR8S&msisdn=8801821965232&keyword=news
    }

}
