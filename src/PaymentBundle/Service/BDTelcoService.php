<?php

namespace PaymentBundle\Service;

use GuzzleHttp\Client;

class BDTelcoService
{

    private $url;
    private $appKey;
    private $client;

    public function __construct($url, $appKey)
    {
        $this->appKey = $appKey;
        $this->url    = $url;
        $this->client = new Client([
            'base_uri' => $this->url,
            'headers'  => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function isSubscribe($msisdn, $keywoard)
    {
        $url   = $this->url . "/subscriptioncheck.aspx";
        $query = array(
            'apikey'  => $this->appKey,
            'msisdn'  => $msisdn,
            'keyword' => $keywoard
        );

        $response = $this->client->request('GET', $url, ['query' => $query]);
        
        return $response->getBody()->getContents();
    }
    public function payment($msisdn)
    {
        $url   = $this->url . "/payment.aspx";
        $query = array(
            'apikey'  => $this->appKey,
            'portalcode' => 'B2922F6A-BF2A-4C9B-CA39-D1CA912FEBFD',
            'msisdn'  => $msisdn,
            'CGWKey' => 'WCH2'
        );

        $response = $this->client->request('GET', $url, ['query' => $query]);
        
        return $response;

        
    }

}
