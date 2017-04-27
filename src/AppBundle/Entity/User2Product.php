<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User2Product
 *
 * @ORM\Table(name="user2_product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\User2ProductRepository")
 */
class User2Product
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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var int
     *
     * @ORM\Column(name="product_id", type="integer")
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="sale_offer_id", type="integer", nullable=true)
     */
    private $saleOfferId;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\GreaterThan(0)
     * @Assert\NotBlank(message="Product quantity can not be blank")
     */
    private $quantity;

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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="sales")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @var SaleOffer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\SaleOffer", inversedBy="soldProducts")
     */
    private $saleOffer;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products")
     */
    private $user;

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
     * Set userId
     *
     * @param integer $userId
     *
     * @return User2Product
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
     * Set productId
     *
     * @param integer $productId
     *
     * @return User2Product
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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return User2Product
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
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
     * @return int
     */
    public function getSaleOfferId()
    {
        return $this->saleOfferId;
    }

    /**
     * @param int $saleOfferId
     */
    public function setSaleOfferId($saleOfferId)
    {
        $this->saleOfferId = $saleOfferId;
    }

    /**
     * @return SaleOffer
     */
    public function getSaleOffer()
    {
        return $this->saleOffer;
    }

    /**
     * @param SaleOffer $saleOffer
     */
    public function setSaleOffer($saleOffer)
    {
        $this->saleOffer = $saleOffer;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * @param \DateTime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
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




}

