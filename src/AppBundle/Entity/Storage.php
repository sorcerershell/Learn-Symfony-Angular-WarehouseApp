<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Storage
 *
 * @ORM\Table(name="storage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageRepository")
 * @UniqueEntity(fields={"name"},message="This storage name is already been used")
 */
class Storage
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_toxic", type="boolean")
     */
    private $isToxic;

    /**
     * @var float
     *
     * @ORM\Column(name="registered_stocks", type="float")
     */
    private $registeredStocks;

    /**
     * @var int
     *
     * @ORM\Column(name="storage_order", type="integer")
     */
    private $storageOrder;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="storage")
     */
    private $inventories;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $this->inventories = new ArrayCollection();
    }


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
     * Set name
     *
     * @param string $name
     * @return Storage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set isToxic
     *
     * @param boolean $isToxic
     * @return Storage
     */
    public function setIsToxic($isToxic)
    {
        $this->isToxic = $isToxic;

        return $this;
    }

    /**
     * Get isToxic
     *
     * @return boolean 
     */
    public function getIsToxic()
    {
        return $this->isToxic;
    }

    /**
     * Is Toxic?
     *
     * @return boolean
     */
    public function isToxic()
    {
        return $this->isToxic;
    }


    /**
     * @return ArrayCollection
     */
    public function getInventories()
    {
        return $this->inventories;
    }

    /**
     * @param ArrayCollection $inventories
     */
    public function setInventories($inventories)
    {
        $this->inventories = $inventories;
    }

    /**
     * @return mixed
     */
    public function getRegisteredStocks()
    {
        return $this->registeredStocks;
    }

    /**
     * @param mixed $registeredStocks
     */
    public function setRegisteredStocks($registeredStocks)
    {
        $this->registeredStocks = $registeredStocks;
    }

    /**
     * @return int
     */
    public function getStorageOrder()
    {
        return $this->storageOrder;
    }

    /**
     * @param int $storageOrder
     */
    public function setStorageOrder($storageOrder)
    {
        $this->storageOrder = $storageOrder;
    }






}
