<?php

namespace App\Form;

use App\Entity\Pakege;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class BoostPakageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //dd($choise);
        $builder
        ->add('name')
            //->add('user_id')
            ->add('price')
            // ->add('referral_networks_id')
            // ->add('client_code')
            // ->add('token')
            // ->add('activation')
            // ->add('unique_code')
            // ->add('referral_link')
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pakege::class,
        ]);
    }
}
