<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            // ->add('roles')
            // ->add('password')
            // ->add('isVerified')
            // ->add('personal_data_id')
            // ->add('username')
            // ->add('role')
            // ->add('referral_link')
            // ->add('pesonal_code')
            // ->add('pakage_status')
            // ->add('pakage_id')
            // ->add('updated_at')
            // ->add('created_at')
            // ->add('wallet')
            // ->add('pkege')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
