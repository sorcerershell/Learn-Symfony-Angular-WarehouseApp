<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/25/17
 * Time: 12:34 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Registry;
use \Doctrine\Common\Persistence\ObjectManager;

class StockInService
{

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|object
     */
    private $em;

    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
    }

    /**
     *
     * Handle Stock In Process
     *
     * @param $product_id
     * @param $qty
     * @param string $expiredAt
     * @return Inventory
     */
    public function handle($product_id, $qty, $expiredAt = "0000-00-00 00:00:00")
    {
        $product = $this->em->getRepository('AppBundle:Product')->find($product_id);
        $storage = $this->em->getRepository('AppBundle:Storage')->getLeastUtilizedStorage();
        $inventory = new Inventory();

        $inventory->setProduct($product);

        if($product->isToxic()) {
            $storage = $this->em->getRepository('AppBundle:Storage')->getToxicStorage();
        }

        if($product->isFood()) {
            $storage = $this->em->getRepository('AppBundle:Storage')->getStorageForFood();
        }

        $inventory->setStorage($storage);

        $inventory->setQuantity($qty);
        $inventory->setExpiredAt(new \DateTime($expiredAt));
        $inventory->setAddedAt(new \DateTime('now'));

        $registeredStocks = $storage->getRegisteredStocks();
        $registeredStocks = $registeredStocks + $inventory->getQuantity();
        $storage->setRegisteredStocks($registeredStocks);

        $this->em->persist($inventory);
        $this->em->persist($storage);
        $this->em->flush();

        return $inventory;


    }
}