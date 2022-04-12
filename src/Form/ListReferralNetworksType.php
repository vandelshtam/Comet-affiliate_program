<?php

namespace App\Form;

use App\Entity\ListReferralNetworks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ListReferralNetworksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Пожалуйста придумайте и введите название вашей рефералной сети, его будут видеть другие участники проекта',
                'required' => true,  
                ])
            //->add('referral_networks_id')
            //->add('owner_id')
            ->add('owner_name')
            ->add('pakege')
            // ->add('pakege', IntegerType::class,[
            //     'label' => 'pakege_id',
            //     //'value' => $this->getUser()->getId(),
            //     'required' => false,  
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListReferralNetworks::class,
        ]);
    }
}
