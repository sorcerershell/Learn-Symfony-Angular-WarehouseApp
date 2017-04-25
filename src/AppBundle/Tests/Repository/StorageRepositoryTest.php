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


class StorageRepositoryTest extends KernelTestCase
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

    public function testGetTotalStorageAvailable()
    {
        $total = $this->em->getRepository('AppBundle:Storage')->getTotalStorage();

        $this->assertInternalType("int", $total);
    }

    public function testGetFirstStorage()
    {
        $storage = $this->em->getRepository('AppBundle:Storage')->getFirstStorage();

        $this->assertEquals(1, $storage->getStorageOrder());
    }

    public function testGetLastStorage()
    {
        $storage = $this->em->getRepository('AppBundle:Storage')->getLastStorage();
        $total = $this->em->getRepository('AppBundle:Storage')->getTotalStorage();

        $this->assertEquals($total, $storage->getStorageOrder());
    }

    public function testGetToxicStorage() {
        $toxic = $this->em->getRepository('AppBundle:Storage')->getToxicStorage();

        $this->assertTrue($toxic->getIsToxic());
    }

    public function testGetNearbyToxicStorage() {
        $toxics = $this->em->getRepository('AppBundle:Storage')->getNearbyToxicStorage();

        $this->assertInternalType("array", $toxics);
        $this->assertGreaterThan(1, count($toxics));
    }

    public function testGetStorageForFood() {
        $toxics = $this->em->getRepository('AppBundle:Storage')->getNearbyToxicStorage();
        $foodStorage = $this->em->getRepository('AppBundle:Storage')->getStorageForFood();

        $this->assertFalse($foodStorage->getIsToxic());

        foreach($toxics as $t) {
            $this->assertNotEquals($t->getId(), $foodStorage->getId());
        }
    }



}