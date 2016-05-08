<?php

namespace PaymentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use PaymentBundle\Entity\Invoice;
use PaymentBundle\Form\InvoiceType;

/**
 * Invoice controller.
 *
 */
class InvoiceController extends FOSRestController
{

    /**
     * Lists all Invoice entities.
     *
     */
    public function indexAction()
    {

        $invoices = $this->get('payment.invoiceService')->get();

        $view = $this->view($invoices, Response::HTTP_OK)
                ->setTemplate("PaymentBundle:Invoice:index.html.twig")
                ->setTemplateVar('invoices')
        ;

        return $this->handleView($view);
    }

    /**
     * Creates a new Invoice entity.
     *
     */
    public function newAction(Request $request)
    {

        $invoice = new Invoice();
        $form    = $this->createForm('PaymentBundle\Form\InvoiceType', $invoice);
        $form->handleRequest($request);


        if ($this->getRequest()->isMethod('POST')) {
            try {
//                $data    = json_decode($request->getContent(), true);
//                $form    = new InvoiceType();
                $newInvoice = $this->get('payment.invoiceService')->post(
                        $form, $invoice
                );

                $routeOptions = array(
                    'id'      => $newInvoice->getId(),
                    '_format' => $this->container->get('request')->get('_format')
                );
                return $this->routeRedirectView('invoice_show', $routeOptions, Codes::HTTP_CREATED);
            } catch (InvalidFormException $exception) {

                return $exception->getForm();
            }

        }

        $returnData = array(
            'invoice' => $invoice,
            'form'    => $form->createView(),
        );

        $view = $this->view($returnData, Response::HTTP_OK)
                ->setTemplate("PaymentBundle:Invoice:new.html.twig")
        ;

        return $this->handleView($view);
    }

    /**
     * Finds and displays a Invoice entity.
     *
     */
    public function showAction(Invoice $invoice)
    {
        $deleteForm = $this->createDeleteForm($invoice);
        $returnData = array(
                    'invoice'     => $invoice,
                    'delete_form' => $deleteForm->createView(),
        );
        $view = $this->view($returnData, Codes::HTTP_OK)
                ->setTemplate("PaymentBundle:Invoice:show.html.twig")
//                ->setTemplateVar('invoice')
        ;

        return $this->handleView($view);

//        
//
//        return $this->render('PaymentBundle:Invoice:show.html.twig', array(
//                    'invoice'     => $invoice,
//                    'delete_form' => $deleteForm->createView(),
//        ));
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     */
    public function editAction(Request $request, Invoice $invoice)
    {
        $deleteForm = $this->createDeleteForm($invoice);
        $editForm   = $this->createForm('PaymentBundle\Form\InvoiceType', $invoice);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($invoice);
            $em->flush();

            return $this->redirectToRoute('invoice_edit', array('id' => $invoice->getId()));
        }

        return $this->render('PaymentBundle:Invoice:edit.html.twig', array(
                    'invoice'     => $invoice,
                    'edit_form'   => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Invoice entity.
     *
     */
    public function deleteAction(Request $request, Invoice $invoice)
    {
        $form = $this->createDeleteForm($invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($invoice);
            $em->flush();
        }

        return $this->redirectToRoute('invoice_index');
    }

    /**
     * Creates a form to delete a Invoice entity.
     *
     * @param Invoice $invoice The Invoice entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Invoice $invoice)
    {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('invoice_delete', array('id' => $invoice->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}


        