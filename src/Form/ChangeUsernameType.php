<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ChangeUsernameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
            ->add('username', TextType::class, [
                'type' => TextType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Пожалуйста введите имя',
                        ]),
                        new Length([
                            'min' => 3,
                            'minMessage' => 'Ваше имя должно содержать е менее  {{ limit }} знаков',
                            // max length allowed by Symfony for security reasons
                            'max' => 250,
                        ]),
                    ],
                    'label' => 'Новое имя в сети',
                ],
                'invalid_message' => 'Измените имя',
                'mapped' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
