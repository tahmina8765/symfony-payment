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

    public function post($formdata)
    {
        $invoice = $this->createInvoice();
        return $this->processForm($invoice, $formdata, 'POST');
    }

    private function processForm($invoice, array $formdata, $method = "PUT")
    {
        $form = $this->formFactory->create(new InvoiceType(), $invoice, array('method' => $method));
        $form->submit($formdata['invoice'], 'PATCH' !== $method);

        if ($form->isValid()) {
            $invoice = $form->getData();
            $this->em->persist($invoice);
            $this->em->flush();
            return $invoice;
        }
        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createInvoice()
    {
        return new $this->entityClass();
    }

}
