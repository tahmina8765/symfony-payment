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

    public function get($id)
    {
        return $this->repository->find($id);
    }

    public function all($limit = 100, $offset = 0)
    {
        return $this->repository->findBy(array(), null, $limit, $offset);
    }

    public function post($formdata)
    {
        $invoice = $this->createInvoice();
        return $this->processForm($invoice, $formdata, 'POST');
    }

    public function put($invoice, $formdata)
    {
        return $this->processForm($invoice, $formdata, 'PUT');
    }

    public function delete($invoice)
    {

        $this->em->remove($invoice);
        $this->em->flush();

        return true;
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
        } else {
            throw new InvalidFormException('Invalid submitted data', $form);
        }
    }

    private function createInvoice()
    {
        return new $this->entityClass();
    }

}
