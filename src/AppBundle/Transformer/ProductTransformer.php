<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/24/17
 * Time: 7:11 PM
 */

namespace AppBundle\Transformer;


use AppBundle\Entity\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{


    public function transform(Product $product)
    {
        $data = [
            'id'   => $product->getId(),
            'name' => $product->getName(),
            'type' => $product->getType(),
        ];

        $inventories = $product->getInventories();

        $in_stock = 0;
        foreach($inventories as $stock) {
            $in_stock = $in_stock + $stock->getQuantity();
        }

        $data['in_stock'] = $in_stock;

        return $data;
    }
}