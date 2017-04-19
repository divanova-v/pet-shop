<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     *
     */
    private $image;

    /**
     * @var string
     * @Assert\Image(
     *     mimeTypes={"image/png", "image/jpeg"},
     *     minWidth = 200,
     *     maxWidth = 400,
     *     minHeight = 200,
     *     maxHeight = 400)
     */
    private $uploadedImage;

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
     * @var int
     * @ORM\Column(name="category_id", type="integer")
     */
    private $category_id;

    /**
     * @var ProductCategory
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductCategory", inversedBy="products")
     */
    private $category;

    /**
     * @var SaleOffer[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SaleOffer", mappedBy="productId")
     */
    private $saleOffers;

    /**
     * @var User2Product[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User2Product", mappedBy="product")
     */
    private $sales;

    public function __construct()
    {
        $this->saleOffers = new ArrayCollection();
        $this->sales = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
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
     * Set image
     *
     * @param string $image
     *
     * @return Product
     */
    public function setImage($image)
    {
        if($image) {
            $this->image = $image;
        }

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Product
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
     * @return Product
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
     * @return string
     */
    public function getUploadedImage()
    {
        return $this->uploadedImage;
    }

    /**
     * @param string $uploadedImage
     */
    public function setUploadedImage($uploadedImage)
    {
        $this->uploadedImage = $uploadedImage;
    }

    /**
     * @return SaleOffer[]|ArrayCollection
     */
    public function getSaleOffers()
    {
        return $this->saleOffers;
    }

    /**
     * @param SaleOffer[]|ArrayCollection $saleOffers
     */
    public function setSaleOffers($saleOffers)
    {
        $this->saleOffers = $saleOffers;
    }

    /**
     * @return User2Product[]|ArrayCollection
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param User2Product[]|ArrayCollection $sales
     */
    public function setSales($sales)
    {
        $this->sales = $sales;
    }

    /**
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param int $category_id
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    /**
     * @return ProductCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param ProductCategory $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * add sale offer to ArrayCollection
     * @param SaleOffer $saleOffer
     */
    public function addSaleOffer(SaleOffer $saleOffer)
    {
        $saleOffer->setProduct($this);
        dump($this);
        $this->saleOffers->add($saleOffer);
    }

    /**
     * remove saleOffer from ArrayCollection
     * @param SaleOffer $saleOffer
     */
    public function removeSaleOffer(SaleOffer $saleOffer)
    {
        //
    }


  }

