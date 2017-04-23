<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * ProductCategory
 *
 * @ORM\Table(name="product_category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductCategoryRepository")
 */
class ProductCategory
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
     *
     * @Assert\NotBlank(message="Category name can not be empty")
     * @Assert\Length(max="255")
     */
    private $name;

    /**
     * @var Product[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return ProductCategory
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
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[]|ArrayCollection $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }


}

