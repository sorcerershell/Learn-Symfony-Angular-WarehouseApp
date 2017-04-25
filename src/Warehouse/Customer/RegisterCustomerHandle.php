<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/8/17
 * Time: 12:19 AM
 */

namespace Warehouse\Customer;

use AppBundle\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Registry;

class RegisterCustomerHandle
{
    /**
     * @var Registry
     */
    protected $registry;

    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
    }

    public function handle(Customer $customer)
    {
        $this->em->persist($customer);
        $this->em->flush();

        return $customer->getId();
    }
}