<?php

require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;

$client = new Client([
    'base_uri' => 'http://localhost/bongo/payment/web/app_dev.php/',
    'headers'  => [
        'User-Agent'   => 'testing/1.0',
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    ]
        ]);

// GET INVOICE
//$response = $client->get('invoices');
//print_r($response->getBody()->getContents());

$postdata = array(
    'countryCode'        => 'BD',
    'gateway'            => 'BD-TELCO',
    'amount'             => '20',
    'currency'           => 'BDT',
    'serviceName'        => 'LATEST',
    'serviceDescription' => '..',
    'name'               => 'Tahmina -test',
    'email'              => 'tahmina@bongobd.com',
    'phone'              => '01716534860',
    'ipnUrl'             => 'http://sdsds.sds',
    'status'             => 'PENDING',
    'appName'            => 'PHP TEST'
);

$response = $client->post('invoices/new', [
    'body' => json_encode(array('invoice' => $postdata))
        ]);

print_r($response->getBody()->getContents());

echo "\n\n";
