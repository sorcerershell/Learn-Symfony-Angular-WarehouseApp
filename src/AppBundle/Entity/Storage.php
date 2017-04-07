<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Storage
 *
 * @ORM\Table(name="storage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StorageRepository")
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
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_toxic", type="boolean")
     */
    private $isToxic;

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


}
