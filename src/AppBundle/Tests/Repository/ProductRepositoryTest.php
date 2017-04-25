<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/25/17
 * Time: 12:48 AM
 */

namespace AppBundle\Tests\Repository;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;


class ProductRepositoryTest extends KernelTestCase
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

    public function testInStock()
    {
        $products = $this->em->getRepository('AppBundle:Product')->findAll();

        foreach($products as $p) {
            $inStock = $this->em->getRepository('AppBundle:Product')->getInStock($p->getId());

            $this->assertInternalType('int',$inStock);
        }


    }



}