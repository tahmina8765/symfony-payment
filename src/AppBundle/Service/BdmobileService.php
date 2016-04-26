<?php

namespace AppBundle\Service;

class BdmobileService
{

    private $appKey;
    
    public function __construct($appKey)
    {
        $this->appKey = $appKey;
    }
    
    public function subscribe()
    {
        return $this->appKey;
    }

}
