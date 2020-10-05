<?php

namespace App\Form;

use App\User\RegistrationData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr'  => ['placeholder' => 'Username']
            ])
            ->add('email', EmailType::class, [
                'attr'  => ['placeholder' => 'Email', 'maxLength' => '180']
            ])
            ->add('password', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'The passwords must match.',
                'first_options'     => [
                    'label' => 'Password',
                    'attr'  => ['placeholder' => 'Your password']
                ],
                'second_options'    => [
                    'label' => 'Repeat your password',
                    'attr'  => ['placeholder' => 'Repeat your password']
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => RegistrationData::class]);
    }
}
