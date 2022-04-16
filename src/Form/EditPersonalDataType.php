<?php

namespace App\Form;

use App\Entity\PersonalData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EditPersonalDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name',TextType::class,[
            'label' => 'Ваше имя',
            'required' => true,  
        ])
        ->add('surname',TextType::class,[
            'label' => 'Ваша фамилия',
            'required' => true,  
        ])
        ->add('phone',TelType::class,[
            'label' => 'Ваш номер телефона',
            'required' => true,  
        ])
        ->add('state', ChoiceType::class, [
            'label' => 'Выберите государство',
            'choices'  => [
                'Armenia' => 'Armenia',
                'Azerbaijan' => 'Azerbaijan',
                'Belarus' => 'Belarus',
                'Estonia' => 'Estonia',
                'Georgia' => 'Georgia',
                'Kazakhstan' => 'Kazakhstan',
                'Kyrgyzstan' => 'Kyrgyzstan',
                'Latvia' => 'Latvia',
                'Lithuania' => 'Lithuania',
                'Moldova' => 'Moldova',
                'Russia' => 'Russia',
                'Tajikistan' => 'Tajikistan',
                'Turkey' => 'Turkey',
                'Turkmenistan' => 'Turkmenistan',
                'Ukraine' => 'Ukraine',
                'Uzbekistan' => 'Uzbekistan',
            ]
        ])
        ->add('region',TextType::class,[
            'label' => 'Область, Штат, Регион и т п',
            'required' => true,  
        ])
        ->add('city',TextType::class,[
            'label' => 'Город',
            'required' => true,  
        ])
        ->add('indexcity', NumberType::class,[
            'label' => 'Почтовый индекс',
            'required' => true,  
        ])
        ->add('street',TextareaType::class, [
            'label' => 'Название вашей улицы',
            'required' => true,  
        ])
        ->add('house', IntegerType::class,[
            'label' => 'Номер дома',
            'required' => true,  
        ])
        ->add('block', TextType::class,[
            'label' => 'Строение, Корпус, Блок',
            'required' => true,  
        ])
        ->add('apartment', IntegerType::class,[
            'label' => 'Номер квартиры',
            //'help' => 'Введите простое число',
            'required' => true,  
        ])
            // ->add('frame')
            // ->add('apartment')
            // ->add('user_id')
            // ->add('indexcity')
            // ->add('users_id')
            // ->add('tel')
            //->add('block')
            // ->add('client_code')
            // ->add('created_at')
            // ->add('updated_at')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PersonalData::class,
        ]);
    }
}
