<?php
/**
 * Created by PhpStorm.
 * User: divanova.v
 * Date: 28-Apr-17
 * Time: 15:27
 */

namespace AppBundle\Service;


use AppBundle\Entity\Promotion;
use AppBundle\Entity\SaleOffer;
use Doctrine\ORM\EntityManager;

class PriceCalculator
{
    /**
     * @var EntityManager
     */
    protected $eManager;

    public function __construct(EntityManager $eManager)
    {
        $this->eManager = $eManager;
    }

    /**
     * @param SaleOffer $saleOffer
     * @return float
     */
    public function calculate(SaleOffer $saleOffer)
    {
        if(!empty($saleOffer->getUser())){
            return $saleOffer->getPrice();
        }
        /**
         * @var $promo Promotion
         */
        $promo = $this->eManager
            ->getRepository(Promotion::class)
            ->getMaxPromotionByOfferAndUser($saleOffer);
        $price = $saleOffer->getPrice();
        return empty($promo) ? $price : $price - ($price * ($promo->getPercent()/100));
    }
}