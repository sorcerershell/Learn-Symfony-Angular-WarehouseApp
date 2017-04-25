<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/25/17
 * Time: 12:34 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Inventory;
use AppBundle\Service\Exception\OutOfStockException;
use Doctrine\Bundle\DoctrineBundle\Registry;
use \Doctrine\Common\Persistence\ObjectManager;

class StockOutService
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
     * @return array
     */
    public function handle($product_id, $qty)
    {
        $product = $this->em->getRepository('AppBundle:Product')->find($product_id);
        $needed_qty = $qty;

        if($qty > $product->getInStock()) {
            throw new OutOfStockException();
        }

        $inventories = $this->em->getRepository('AppBundle:Inventory')->getProductInventories($product);
        $stocks = [];

        foreach($inventories as $i) {
            $stocks[] = $i;

            if($i->getQuantity() >= $needed_qty) {
                break;
            }

            $needed_qty = $needed_qty - $i->getQuantity();
        }

        return $stocks;
    }
}