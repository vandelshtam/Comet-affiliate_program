<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ChangeRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder    
        ->add('role', ChoiceType::class, [
            'label' => 'Выбрать роль пользователя',
            'choices'  => [
                'Admin' => 'ROLE_ADMIN',
                'SuperAdmin' => 'ROLE_SUPER_ADMIN',
                'User' => 'ROLE_USER',
            ]
        ])
        //->add('role')
    //     ->addModelTransformer(new CallbackTransformer(
    //         function ($rolesAsArray) {
    //              return count($rolesAsArray) ? $rolesAsArray[0]: null;
    //         },
    //         function ($rolesAsString) {
    //              return [$rolesAsString];
    //         }
    // ))
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
