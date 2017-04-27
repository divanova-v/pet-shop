<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\SaleOffer;
use AppBundle\Entity\User2Product;
use AppBundle\Repository\ProductRepository;
use AppBundle\Repository\User2ProductRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSaleOfferNewType extends SaleOfferType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('product',
            EntityType::class,
            [
                'class' => Product::class,
                'placeholder' => 'Choose a product',
                'choice_label' => 'name',
                'query_builder' => function (ProductRepository $er) {
                    $query = $er
                        ->createQueryBuilder('p')
                        ->join('p.sales',
                            'sp',
                            'WITH',
                            'sp.userId = :id')
                        ->orderBy('p.createdOn', 'DESC')
                        ->setParameters(['id' => 1]);
                    return $query;
                }
            ]);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SaleOffer::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_user_sale_offer_new_type';
    }
}
