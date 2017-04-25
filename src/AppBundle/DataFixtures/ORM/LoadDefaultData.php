<?php

namespace AppBundle\DataFixtures\ORM;
use AppBundle\Entity\Customer;
use AppBundle\Entity\Product;
use AppBundle\Entity\Storage;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDefaultData implements FixtureInterface
{
    public function load(ObjectManager $objectManager)
    {
        $faker = \Faker\Factory::create();

        $productTypes = Product::getTypes();
        $types = [];
        foreach($productTypes as $key => $val) {
            $types[] = [
                'key' => $key,
                'value' => $val,
            ];
        }

        for($i=1;$i<=10;$i++) {
            $customer = new Customer();
            $customer->setName($faker->name);
            $customer->setEmail($faker->email);
            $customer->setAddress($faker->address);
            $objectManager->persist($customer);

            $product = new Product();
            $product->setName($faker->sentence(3));

            $product->setType($types[rand(0,3)]['key']);
            $objectManager->persist($product);

            $storage = new Storage();
            $storage->setName('Alley '.$i);
            $storage->setIsToxic(($i==3) ? true : false);
            $storage->setRegisteredStocks(0);
            $storage->setStorageOrder($i);
            $objectManager->persist($storage);
        }

        $objectManager->flush();



    }
}