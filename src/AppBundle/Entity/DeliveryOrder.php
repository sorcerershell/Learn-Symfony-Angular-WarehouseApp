<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryOrder
 *
 * @ORM\Table(name="delivery_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeliveryOrderRepository")
 */
class DeliveryOrder
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_at", type="datetime")
     */
    private $orderAt;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set orderAt
     *
     * @param \DateTime $orderAt
     * @return DeliveryOrder
     */
    public function setOrderAt($orderAt)
    {
        $this->orderAt = $orderAt;

        return $this;
    }

    /**
     * Get orderAt
     *
     * @return \DateTime 
     */
    public function getOrderAt()
    {
        return $this->orderAt;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return DeliveryOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }
}
