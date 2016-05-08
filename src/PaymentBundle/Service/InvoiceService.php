<?php

namespace PaymentBundle\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Doctrine\ORM\EntityManager;
use PaymentBundle\Form\InvoiceType;
use PaymentBundle\Entity\Invoice;
use PaymentBundle\Exception\InvalidFormException;

class InvoiceService
{

    private $em;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(EntityManager $entityManager, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->em          = $entityManager;
        $this->entityClass = $entityClass;
        $this->repository  = $this->em->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
    }

    public function get()
    {
        $invoices = $this->repository->findAll();
        return $invoices;
    }

    public function post($form, $invoice)
    {
        
        if ($form->isValid()) {
            $this->em->persist($invoice);
            $this->em->flush();
            return $invoice;
        }
        
        dump($form->getErrors(true));die();
//        $invoice = $this->createInvoice();
//        return $this->processForm($invoice, $parameters, 'POST');
    }

    private function processForm($invoice, array $parameters, $method = "PUT")
    {

        $form = $this->formFactory->create(new InvoiceType(), $invoice, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method);

        if ($form->isValid()) {
            $invoice = $form->getData();
            $this->em->persist($invoice);
            $this->em->flush();
            return $invoice;
        }
        dump($form->getErrors(true));die();
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createInvoice()
    {
        return new $this->entityClass();
    }

}
