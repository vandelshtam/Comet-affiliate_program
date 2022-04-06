<?php

namespace App\Form;

use App\Entity\Wallet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class WalletExchangeUsdtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usdt')
            //->add('etherium')
            //->add('bitcoin')
            ->add('cometpoin', ChoiceType::class, [
                'choices'  => [
                    'Cometpoin' => 1,
                    'Etherium' => 2,
                    'Bitcoin' => 3,
                ]
            ])
            //->add('user_id')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wallet::class,
        ]);
    }
}
