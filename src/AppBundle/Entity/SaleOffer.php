<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * SaleOffer
 *
 * @ORM\Table(name="sale_offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SaleOfferRepository")
 */
class SaleOffer
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
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Add description for the sale offer please")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     *
     * @Assert\NotBlank(message="Price can not be empty")
     * @Assert\Type(type="double", message="The value {{value}} is not a valid number")
     * @Assert\GreaterThan(value="0.01", message="Price must be a positive value")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     *
     * @Assert\NotBlank(message="What quantity of this product you offer to sale?")
     * @Assert\Type(type="integer")
     */
    private $quantity;

    /**
     * @var int
     *
     * @ORM\Column(name="showOrder", type="integer")
     */
    private $showOrder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime")
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime")
     */
    private $updatedOn;

    /**
     * @var Product
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="saleOffers")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="saleOffers")
     */
    private $user;

    /**
     * @var User2Product[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User2Product", mappedBy="saleOffer")
     */
    private $soldProducts;

    public function __construct()
    {
        $this->soldProducts = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set productId
     *
     * @param integer $productId
     *
     * @return SaleOffer
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return SaleOffer
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return SaleOffer
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return SaleOffer
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set quantoty
     *
     * @param integer $quantity
     *
     * @return SaleOffer
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantoty
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set showOrder
     *
     * @param integer $showOrder
     *
     * @return SaleOffer
     */
    public function setShowOrder($showOrder)
    {
        $this->showOrder = $showOrder;

        return $this;
    }

    /**
     * Get showOrder
     *
     * @return int
     */
    public function getShowOrder()
    {
        return $this->showOrder;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return SaleOffer
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     *
     * @return SaleOffer
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User2Product[]|ArrayCollection
     */
    public function getSoldProducts()
    {
        return $this->soldProducts;
    }

    /**
     * @param User2Product[]|ArrayCollection $soldProducts
     */
    public function setSoldProducts($soldProducts)
    {
        $this->soldProducts = $soldProducts;
    }



}

