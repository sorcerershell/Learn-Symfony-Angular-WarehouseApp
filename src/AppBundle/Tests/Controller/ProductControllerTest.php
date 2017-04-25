<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
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

    public function testGetProducts()
    {

        $client = $this->getClient();

        $crawler = $client->request('GET', '/api/v1/products/');


        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'content-type',
                'application/json'
            ),
            'the "Content-Type" header is not "application/json"' // optional message shown on failure
        );

        $response = json_decode($client->getResponse()->getContent());
        $this->assertGreaterThan(1, count($response->data));

    }

    public function testPostProductWithValidData()
    {
        $faker = \Faker\Factory::create();
        $client = $this->getClient();

        $productTypes = Product::getTypes();
        $types = [];
        foreach($productTypes as $key => $val) {
            $types[] = [
                'key' => $key,
                'value' => $val,
            ];
        }

        $data = [
                'product' => [
                    'name'       => $faker->name,
                    'type'    => $types[rand(1,4)]['key'],
                ],
        ];

        $crawler = $client->request('POST','/api/v1/products/', [], [], [
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
            $response->data->productId,
            'the Product ID is invalid: '.$response->data->productId
        );
    }

    public function testUpdateProduct()
    {
        $faker = \Faker\Factory::create();

        $client = $this->getClient();

        // Get All Product
        $crawler = $client->request('GET', '/api/v1/products/');

        $response = json_decode($client->getResponse()->getContent());

        $productId = $response->data[0]->id;



        $client = $this->getClient();
        $data = [
            'product' => [
                'name'    => $faker->name,
                'type'    => rand(1,4),
            ],
        ];


        $crawler = $client->request('POST','/api/v1/product/'.$productId, [], [], [
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
            $response->data->productId,
            'the Product ID is invalid: '.$response->data->productId
        );
    }

    public function testGetProduct()
    {
        $client = $this->getClient();

        // Get All Product
        $crawler = $client->request('GET', '/api/v1/products/');

        $response = json_decode($client->getResponse()->getContent());

        $productId = $response->data[0]->id;

        $crawler = $client->request('GET', '/api/v1/product/'.$productId);

        $response = json_decode($client->getResponse()->getContent());

        $this->assertNotEmpty($response->data);
    }

    public function testDeleteProduct()
    {
        $client = $this->getClient();

        // Get All Product
        $crawler = $client->request('GET', '/api/v1/products/');

        $response = json_decode($client->getResponse()->getContent());

        $productId = $response->data[0]->id;

        $crawler = $client->request('DELETE', '/api/v1/product/'.$productId);

        $response = json_decode($client->getResponse()->getContent());

        $this->assertTrue($response->success);
    }

}
