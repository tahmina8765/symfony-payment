<?php

namespace PaymentBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//                ->add('invoiceId')
                ->add('countryCode')
                ->add('gateway')
                ->add('amount')
                ->add('currency')
                ->add('serviceName')
                ->add('serviceDescription')
                ->add('name')
                ->add('email')
                ->add('phone')
                ->add('ipnUrl')
                ->add('status')
                ->add('appName')
//                ->add('created', 'datetime')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'data_class' => 'PaymentBundle\Entity\Invoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'invoice';
    }

}
