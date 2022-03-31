<?php

namespace App\Form;

use App\Entity\SettingOptions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingOptionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payments_singleline')
            ->add('payments_direct')
            ->add('cash_back')
            ->add('all_price_pakage')
            ->add('accrual_limit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SettingOptions::class,
        ]);
    }
}
