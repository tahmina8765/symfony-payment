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
use PaymentBundle\Exception\InvalidFormException;

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

        // $this->denyAccessUnlessGranted('ROLE_API');  

        $invoices = $this->get('payment.invoiceService')->all();

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
        if ($this->getRequest()->isMethod('GET')) {

            $form = $this->container->get('form.factory')->create(new InvoiceType(), new Invoice(), array('method' => 'POST'));

            $returnData = array(
                'form' => $form->createView(),
            );

            $view = $this->view($returnData, Response::HTTP_OK)
                    ->setTemplate("PaymentBundle:Invoice:new.html.twig")
            ;
        }

        if ($this->getRequest()->isMethod('POST')) {
            try {
                $newInvoice   = $this->get('payment.invoiceService')->post($request->request->all());
                $routeOptions = array(
                    'id' => $newInvoice->getId()
                );
                $view         = $this->routeRedirectView('invoice_show', $routeOptions, Response::HTTP_CREATED);
            } catch (InvalidFormException $exception) {

                $view = $this->view($exception->getForm(), Response::HTTP_BAD_REQUEST)
                        ->setTemplate("PaymentBundle:Invoice:edit.html.twig")
                ;
            }
        }
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
        $view       = $this->view($returnData, Response::HTTP_OK)
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

        if ($this->getRequest()->isMethod('GET')) {

            $form = $this->container->get('form.factory')->create(new InvoiceType(), $invoice, array('method' => 'PUT'));

            $returnData = array(
                'edit_form' => $form->createView(),
            );

            $view = $this->view($returnData, Response::HTTP_OK)
                    ->setTemplate("PaymentBundle:Invoice:edit.html.twig")
            ;
        }


        if ($this->getRequest()->isMethod('POST') || $this->getRequest()->isMethod('PUT')) {

            try {
                $formdata   = ($request->request->all());
                $newInvoice = $this->get('payment.invoiceService')->put($invoice, $formdata);

                $routeOptions = array(
                    'id' => $newInvoice->getId()
                );
                $view         = $this->routeRedirectView('invoice_show', $routeOptions, Response::HTTP_ACCEPTED);
            } catch (InvalidFormException $exception) {

                $view = $this->view($exception->getForm(), Response::HTTP_BAD_REQUEST)
                        ->setTemplate("PaymentBundle:Invoice:edit.html.twig")
                ;
            }
        }
        return $this->handleView($view);
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
