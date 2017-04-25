<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CustomerControllerTest extends WebTestCase
{


    protected function getClient() {
        $baseUrl    = "http://localhost:8000/app_test.php";
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

        $crawler = $client->request('GET', '/api/v1/customers/');


        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'content-type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

    }

    public function testPostCustomerWithValidData()
    {
        $faker = \Faker\Factory::create();
        $client = $this->getClient();
        $data = [
                'customer' => [
                    'name'       => $faker->name,
                    'email'      => $faker->email,
                    'address'    => $faker->address,
                ],
        ];

        $crawler = $client->request('POST','/api/v1/customers', [], [], [
            'CONTENT_TYPE'          => 'application/json',
        ],json_encode($data));

        $response = json_decode($client->getResponse()->getContent());

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'content-type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

        $this->assertTrue(
            $response->success,
            'the "response.success" header is not "true" ==>'.$client->getResponse()->getContent() // optional message shown on failure
        );

        $this->assertGreaterThan(
            0,
            $response->data->customerId,
            'the Customer ID is invalid: '.$response->data->customerId
        );
    }

    public function testGetCustomer()
    {
        $client = $this->getClient();

        // Get All Customer
        $crawler = $client->request('GET', '/api/v1/customers/');

        $response = json_decode($client->getResponse()->getContent());

        $customerId = $response->customers[0]->id;

        $crawler = $client->request('GET', '/api/v1/customer/'.$customerId);

        $response = json_decode($client->getResponse()->getContent());

        $this->assertNotEmpty($response->customer);
    }

    public function testDeleteCustomer()
    {
        $client = $this->getClient();

        // Get All Customer
        $crawler = $client->request('GET', '/api/v1/customers/');

        $response = json_decode($client->getResponse()->getContent());

        $customerId = $response->customers[0]->id;

        $crawler = $client->request('DELETE', '/api/v1/customer/'.$customerId);

        $response = json_decode($client->getResponse()->getContent());

        $this->assertTrue($response->success);

    }

}
