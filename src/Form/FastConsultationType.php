<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class FastConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Пожалуйста введите ваше имя.',
                'mapped' => true,
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Введите ваш електронный почтовый адрес.',
                'mapped' => true,
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => 'Введите ваш номер телефона в формате страна(+ххх)номер(ххх ххх хх хх)',
                'mapped' => true,
                'required' => true,
            ])
            ->add('question', TextareaType::class, [
                'label' => 'Введите ваш вопрос',
                'mapped' => true,
                'required' => true,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
