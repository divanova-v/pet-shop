<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{

    const INITIAL_CASH = 5000;
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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(message="This is not a valid email")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     *
     * @Assert\NotBlank(message="Full name is required")
     * @Assert\Type(type="string")
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var int
     * @ORM\Column(name="cash", type="integer")
     */
    private $cash;

    /**
     * @var string
     * @Assert\NotBlank(message="Password is required")
     * @Assert\Length(min="4")
     */
    private $password_row;

    /**
     * @var Product[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User2Product", mappedBy="userId")
     */
    private $products;

    /**
     * @var SaleOffer[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\SaleOffer", mappedBy="user");
     */
    private $saleOffers;

    /**
     * @var ArrayCollection |Role[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Role")
     * @ORM\JoinTable(name="users_roles",
     *     joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    public function __construct()
    {
        $this->saleOffers = new ArrayCollection();
        $this->roles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getPasswordRow()
    {
        return $this->password_row;
    }

    /**
     * @param mixed $password_row
     */
    public function setPasswordRow($password_row)
    {
        $this->password_row = $password_row;
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function getRoles()
    {
        $userRoles = [];
        foreach ($this->roles as $role) {
            $userRoles[] = $role->getName();
        }
        return $userRoles;
    }

    /**
     * add role to user
     * @param Role $role
     * @return $this
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
        return $this;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {

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
     * @param SaleOffer $saleOffer
     */
    public function addSaleOffer(SaleOffer $saleOffer)
    {
        $this->getSaleOffers()->add($saleOffer);
    }

    /**
     * @return int
     */
    public function getCash()
    {
        return $this->cash;
    }

    /**
     * @param int $cash
     */
    public function setCash($cash)
    {
        $this->cash = $cash;
    }


}

