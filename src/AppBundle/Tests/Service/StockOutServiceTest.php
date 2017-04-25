<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/25/17
 * Time: 7:48 AM
 */

namespace AppBundle\Tests\Service;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use AppBundle\Service\Exception\OutOfStockException;

class StockOutServiceTest extends KernelTestCase
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

    public function testStockOutWithQuantityMoreThanAvailable()
    {
        $this->expectException(\AppBundle\Service\Exception\OutOfStockException::class);
        $products = $this->em->getRepository('AppBundle:Product')->findAll();
        $product = $products[0];
        $stock = $product->getInStock();
        $requestQty = $stock + 10;

        $stocks = $this->container->get('app.service.stock_out_service')->handle($product->getId(), $requestQty);

        $this->assertInternalType('array', $stocks);
    }

    public function testHalfStockOut()
    {
        $products = $this->em->getRepository('AppBundle:Product')->findAll();
        $product = $products[0];
        $stock = $product->getInStock();
        $requestQty = $stock / 2;

        $stocks = $this->container->get('app.service.stock_out_service')->handle($product->getId(), $requestQty);
        var_dump($stocks);
        $this->assertInternalType('array', $stocks);
    }




}