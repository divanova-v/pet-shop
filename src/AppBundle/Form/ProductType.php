<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('category', EntityType::class,
                [
                    'class' => ProductCategory::class,
                    'placeholder' => 'Choose a category',
                    'choice_label' => 'name'
                ])
            ->add('uploadedImage',FileType::class,
                [
                    'data_class' => null,
                    'required' => false
                ])
            ->add('saleOffers', CollectionType::class,
                [
                    'entry_type' => SaleOfferType::class,
                    'by_reference'=> false
                ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'yudjiepetshopbundle_product';
    }


}
