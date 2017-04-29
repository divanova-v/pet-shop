<?php

namespace AppBundle\Form;

use AppBundle\Entity\Promotion;
use AppBundle\Entity\SaleOffer;
use AppBundle\Repository\SaleOfferRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionSaleOfferType extends PromotionGeneralType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('saleOffers', EntityType::class, [
            'class' => SaleOffer::class,
            'placeholder' => 'Choose an offer',
            'choice_label' => 'product.name',
            'multiple' => true,
            'expanded' => true,
            'query_builder' => function (SaleOfferRepository $er) {
                return $er->getQBForShopSaleOffers();
            }
        ]);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Promotion::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_promotion_sale_offer_type';
    }
}
