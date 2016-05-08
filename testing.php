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

$response = $client->get('invoices');

print_r($response->getBody()->getContents());
echo "\n\n";
