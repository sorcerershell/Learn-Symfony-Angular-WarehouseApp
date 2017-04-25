<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ExclusionPolicy("all")
 */
class Product
{
    const GENERAL = "general";
    const FOOD  = "food";
    const PERISHABLE = "perishable";
    const TOXIC = "toxic";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", nullable=true)
     * @Expose
     * @Assert\NotBlank()
     */
    private $type;


    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Inventory", mappedBy="product", orphanRemoval=true)
     */
    private $inventories;

    /**
     * Product constructor.
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
     * @return Product
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
     * Set type
     *
     * @param string $type
     * @return Product
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Product Types
     * @return array
     */
    public static function getTypes()
    {
        return [
            self::GENERAL       => "General Goods",
            self::FOOD          => "Foods",
            self::PERISHABLE    => "Perishable Goods",
            self::TOXIC         => "Toxic Goods",
        ];
    }

    /**
     * Get expireAt
     *
     * @return \DateTime 
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * @return ArrayCollection
     */
    public function getInventories()
    {
        return $this->inventories;
    }

    public function removeInventories()
    {
        unset($this->inventories);
    }


    /**
     * @param ArrayCollection $inventories
     */
    public function setInventories($inventories)
    {
        $this->inventories = $inventories;
    }

    public function isToxic()
    {
        if($this->getType() == self::TOXIC) return true;

        return false;
    }

    public function isFood()
    {
        if($this->getType() == self::FOOD) return true;

        return false;
    }

    public function isPerishable()
    {
        if($this->getType() == self::PERISHABLE) return true;

        return false;
    }

    public function getInStock()
    {
        $sum = 0;
        foreach($this->getInventories() as $i) {
            $sum = $sum + $i->getQuantity();
        }

        return $sum;
    }
}
