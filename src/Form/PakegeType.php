<?php

namespace App\Form;

use App\Entity\Pakege;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PakegeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', ChoiceType::class, [
                'label' => 'Выберите пакет',
                'choices'  => [
                    'Starter' => 'Starter',
                    'Basic' => 'Basic',
                    'Networker' => 'Networker',
                    'Business' => 'Business',
                    'Trader' => 'Trader',
                    'VIP' => 'VIP',
                ]
            ])
            ->add('activation')
            ->add('price')
            ->add('referral_link', TextType::class, [
                'label' => 'Вставьте реферальную ссылку или код клиента',
                'required' => false,  
                ])
                ->add('unique_code', TextType::class,[
                    'label' => 'unique_code',
                    //'value' => $this->getUser()->getId(),
                    'required' => false,  
                ])
            // ->add('client_code')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pakege::class,
        ]);
    }
}
