<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\User2Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class User2ProductNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class,
                [
                    'class' => Product::class,
                    'placeholder' => 'Choose a product',
                    'choice_label' => 'name'
                ])
            ->add('quantity', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User2Product::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_user2product_new_type';
    }
}
