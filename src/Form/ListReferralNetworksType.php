<?php

namespace App\Form;

use App\Entity\ListReferralNetworks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListReferralNetworksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('referral_networks_id')
            ->add('owner_id')
            ->add('owner_name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListReferralNetworks::class,
        ]);
    }
}
