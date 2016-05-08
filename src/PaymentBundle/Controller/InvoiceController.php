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
        $view = $this->view($invoice, Response::HTTP_OK)
                ->setTemplate("PaymentBundle:Invoice:show.html.twig")
                ->setTemplateVar('invoice')
        ;

        return $this->handleView($view);
    }

    /**
     * Displays a form to edit an existing Invoice entity.
     *
     */
    public function editAction(Request $request, Invoice $invoice)
    {

        if ($this->getRequest()->isMethod('GET')) {

            $form = $this->container->get('form.factory')->create(new InvoiceType(), $invoice, array('method' => 'POST'));

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
        if ($this->getRequest()->isMethod('GET')) {

            // $form = $this->container->get('form.factory')->create(new InvoiceType(), $invoice, array('method' => 'DELETE'));
            $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('invoice_delete', array('id' => $invoice->getId())))
            ->setMethod('DELETE')
            ->getForm();
            $returnData = array(
                'delete_form' => $form->createView(),
            );

            $view = $this->view($returnData, Response::HTTP_OK)
                    ->setTemplate("PaymentBundle:Invoice:delete.html.twig")
            ;
        }

        if ($this->getRequest()->isMethod('DELETE')) {
            try {

                $result = $this->get('payment.invoiceService')->delete($invoice);
                $view   = $this->routeRedirectView('invoice_index', array(), Response::HTTP_NO_CONTENT);
            } catch (InvalidFormException $exception) {
                $view = $this->view($exception->getForm(), Response::HTTP_BAD_REQUEST)
                        ->setTemplate("PaymentBundle:Invoice:delete.html.twig")
                ;
            }
        }

        return $this->handleView($view);
    }

}
