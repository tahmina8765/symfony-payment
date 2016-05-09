<?php

namespace PaymentBundle\Model;

Interface BDTelcoInterface
{
    /**
     * Set MSISDN
     *
     * @param string $MSISDN
     * @return BDTelcoInterface
     */
    public function setMSISDN($MSISDN);
    /**
     * Get MSISDN
     *
     * @return string 
     */
    public function getMSISDN();
    /**
     * Set CGWKEY
     *
     * @param string $CGWKEY
     * @return BDTelcoInterface
     */
    public function setCGWKEY($CGWKEY);
    /**
     * Get CGWKEY
     *
     * @return string 
     */
    public function getCGWKEY();
}

