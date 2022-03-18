<?php

namespace App\Form;

use App\Entity\ReferralNetwork;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReferralNetworkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Ваше имя участника рефералной сети, его будут видеть другие участники проекта',
            'required' => true,
            ])  
            //->add('user_id')
            //->add('user_status')
            //->add('personal_data_id')
            ->add('member_code')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReferralNetwork::class,
        ]);
    }
}
