<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', EntityType::class,
            [
                'class' => ProductCategory::class,
                'placeholder' => 'Choose a category',
                'choice_label' => 'name'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults(array(
//            'data_class' => ProductCategory::class
//        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_filter_type';
    }
}
