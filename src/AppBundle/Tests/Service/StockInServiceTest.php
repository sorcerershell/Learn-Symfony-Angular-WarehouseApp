<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/25/17
 * Time: 5:50 AM
 */

namespace AppBundle\Tests\Service;


use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class StockInServiceTest extends KernelTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var EntityManager;
     */
    private $em;

    public function setUp()
    {
        self::bootKernel();
        $this->container = static::$kernel->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();
    }

    public function testStockInToxicProducts()
    {
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy(['type' => Product::TOXIC]);
        $storage = $this->em->getRepository('AppBundle:Storage')->getToxicStorage();
        $qty     = 100;
        $registeredStocks = $storage->getRegisteredStocks() + 100;

        $inventory = $this->container->get('app.service.stock_in_service')->handle($product->getId(), $qty);

        $this->assertTrue($inventory->getStorage()->getIsToxic());

        $this->assertEquals($registeredStocks, $inventory->getStorage()->getRegisteredStocks());

    }

    public function testStockInFoodProducts()
    {
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy(['type' => Product::FOOD]);
        $qty     = 100;

        $inventory = $this->container->get('app.service.stock_in_service')->handle($product->getId(), $qty);

        $this->assertFalse($inventory->getStorage()->getIsToxic());

    }

    public function testStockInPerishableProducts()
    {
        $product = $this->em->getRepository('AppBundle:Product')->findOneBy(['type' => Product::PERISHABLE]);

        $qty     = 100;
        $expiredAt = '2017-04-25 10:00:00';

        $inventory = $this->container->get('app.service.stock_in_service')->handle($product->getId(), $qty, $expiredAt);

        $this->assertEquals(new \DateTime($expiredAt), $inventory->getExpiredAt());

    }


}