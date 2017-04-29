<?php

namespace AppBundle\Form;

use AppBundle\Entity\Promotion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class PromotionUserType extends PromotionGeneralType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('registerDate', DateType::class, [
            'required' => false,
            'label' => 'Users registered before'
        ])
            ->add('cash', MoneyType::class, [
                'required' => false,
                'label' => 'Users with cash more than'
            ]);
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_promotion_user_type';
    }
}
