<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{

    protected function getClient() {
        $baseUrl    = getenv('TEST_BASE_URL');
        $client     = static::createClient([
            'base_url' => $baseUrl,
            'defaults' => [
                'exceptions' => false
            ]
        ]);
        $client->followRedirects();

        return $client;
    }

    public function testGetCustomers()
    {
        $client = $this->getClient();

        $crawler = $client->request('GET', '/api/v1/customers');


        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'content-type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );
    }

    public function testPostCustomer()
    {
        $client = $this->getClient();

        $crawler = $client->request('POST','/api/v1/customer', [
           'appbundle_customer' => [
               'name'       => 'John Doe',
               'email'      => 'john.doe@gmail.com',
               'address'    => 'Jalan Mawar No. 49',
           ]
        ]);
        $response = json_decode($client->getResponse()->getContent());
        var_dump($response);
        $this->assertTrue($response->success, 'response says unsuccessful operation');



    }
}
