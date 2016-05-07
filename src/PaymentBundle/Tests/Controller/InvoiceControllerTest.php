<?php

namespace PaymentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class InvoiceControllerTest extends WebTestCase
{

    protected function assertJsonResponse($response, $statusCode = 200) {
            $this->assertEquals(
                $statusCode, $response->getStatusCode(),
                $response->getContent()
            );
            $this->assertTrue(
                $response->headers->contains('Content-Type', 'application/json'),
                $response->headers
            );
    }
    
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/invoices/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /invoice/");

        $crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $invoiceId = 'Test-' . date('Ymdhis');
        $form      = $crawler->selectButton('Create')->form(array(
            'invoice[invoiceId]'            => $invoiceId,
            'invoice[countryCode]'          => 'BD',
            'invoice[gateway]'              => 'BD-TELCO',
            'invoice[amount]'               => '20',
            'invoice[currency]'             => 'BDT',
            'invoice[serviceName]'          => 'LATEST',
            'invoice[serviceDescription]'   => '..',
            'invoice[name]'                 => 'Tahmina -test',
            'invoice[email]'                => 'tahmina@bongobd.com',
            'invoice[phone]'                => '01716534860',
            'invoice[ipnUrl]'               => 'http://sdsds.sds',
            'invoice[status]'               => 'PENDING',
            'invoice[appName]'              => 'UNIT TEST',
            'invoice[created][date][year]'  => '2016',
            'invoice[created][date][month]' => '5',
            'invoice[created][date][day]'   => '1',
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("' . $invoiceId . '")')->count(), 'Missing element td:contains("' . $invoiceId . '")');

        // Edit the entity
//        $crawler = $client->click($crawler->selectLink('Back to the list')->link());
//        $crawler = $client->click($crawler->selectLink('edit')->link());
//
//        
//        $form = $crawler->selectButton('Update')->form(array(
//            'invoice[invoiceId]'            => $invoiceId,
//            'invoice[countryCode]'          => 'IN',
//            'invoice[gateway]'              => 'BD-TELCO',
//            'invoice[amount]'               => '20',
//            'invoice[currency]'             => 'BDT',
//            'invoice[serviceName]'          => 'LATEST',
//            'invoice[serviceDescription]'   => '..',
//            'invoice[name]'                 => 'Tahmina -test',
//            'invoice[email]'                => 'tahmina@bongobd.com',
//            'invoice[phone]'                => '01716534860',
//            'invoice[ipnUrl]'               => 'http://sdsds.sds',
//            'invoice[status]'               => 'PENDING',
//            'invoice[appName]'              => 'UNIT TEST',
//            'invoice[created][date][year]'  => '2016',
//            'invoice[created][date][month]' => '05',
//            'invoice[created][date][day]'   => '01',
//        ));
//
//        $client->submit($form);
//        $crawler = $client->followRedirect();
//
        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('td:contains("' . $invoiceId . '")')->count(), 'Missing element [value="IN"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
//        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    public function testJsonPostPageAction()
    {
        $postdata     = json_encode(array(
            'invoice[invoiceId]'            => 'aaa',
            'invoice[countryCode]'          => 'BD',
            'invoice[gateway]'              => 'BD-TELCO',
            'invoice[amount]'               => '20',
            'invoice[currency]'             => 'BDT',
            'invoice[serviceName]'          => 'LATEST',
            'invoice[serviceDescription]'   => '..',
            'invoice[name]'                 => 'Tahmina -test',
            'invoice[email]'                => 'tahmina@bongobd.com',
            'invoice[phone]'                => '01716534860',
            'invoice[ipnUrl]'               => 'http://sdsds.sds',
            'invoice[status]'               => 'PENDING',
            'invoice[appName]'              => 'UNIT TEST',
            'invoice[created][date][year]'  => '2016',
            'invoice[created][date][month]' => '5',
            'invoice[created][date][day]'   => '1',
        ));
        
        $this->client = static::createClient();
        $this->client->request(
                'POST', '/invoices/new', array(), array(), array('CONTENT_TYPE' => 'application/json'), $postdata
        );
        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

}
