<?php

namespace App\Form;

use App\Entity\TransactionTable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('network_id')
            ->add('user_id')
            ->add('pakage_id')
            ->add('cash')
            ->add('direct')
            ->add('withdrawal_to_wallet')
            ->add('withdrawal')
            ->add('created_at')
            ->add('updated_at')
            ->add('application_withdrawal')
            ->add('application_withdrawal_status')
            ->add('network_activation_id')
            ->add('type')
            ->add('pakage_price')
            ->add('wallet_id')
            ->add('somme')
            ->add('token')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransactionTable::class,
        ]);
    }
}
