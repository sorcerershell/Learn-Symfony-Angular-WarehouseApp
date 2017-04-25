<?php
/**
 * Created by PhpStorm.
 * User: gusprie
 * Date: 4/8/17
 * Time: 12:18 AM
 */

namespace Warehouse\Customer;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class RegisterCustomerCommand
 * @package Warehouse\Customer
 */
class RegisterCustomerCommand
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var string
     * @Assert\Email()
     */
    protected $email;

    /**
     * @var string
     */
    protected $address;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }



}