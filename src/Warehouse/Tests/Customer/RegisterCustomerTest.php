<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/8/17
 * Time: 12:59 AM
 */

namespace Warehouse\Tests\Customer;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Warehouse\Customer\RegisterCustomerCommand;
use Doctrine\ORM\EntityManager;


class Test extends KernelTestCase
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
        $this->truncateTable('AppBundle:Customer');
    }

    public function testRegisterCustomer()
    {
        $customer = new RegisterCustomerCommand();
        $customer->setName('Agus Supriyadi');
        $customer->setEmail('supriyadi@gmail.com');
        $customer->setAddress('Jalan Mawar No.50');

        $this->container->get('warehouse.customer.register_customer')->handle($customer);

        $customers = $this->em->getRepository('AppBundle:Customer')->findByEmail('supriyadi@gmail.com');

        $this->assertCount(1, $customers);
    }

    protected function truncateTable($className) {
        $cmd = $this->em->getClassMetadata($className);
        $connection = $this->em->getConnection();
        $connection->beginTransaction();

        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $connection->query('DELETE FROM '.$cmd->getTableName());
            // Beware of ALTER TABLE here--it's another DDL statement and will cause
            // an implicit commit.
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
        }
    }

}
