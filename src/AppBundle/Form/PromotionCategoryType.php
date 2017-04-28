<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProductCategory;
use AppBundle\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionCategoryType extends PromotionGeneralType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('categories', EntityType::class, [
            'class' => ProductCategory::class,
            'placeholder' => 'Choose a category',
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true,
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
        return 'app_bundle_promotion_category_type';
    }
}
