<?php

namespace App\Form;

use App\User\RegistrationData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.label.name',
                'attr'  => ['placeholder' => 'form.label.username']
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.label.email',
                'attr'  => [
                    'placeholder' => 'form.label.email',
                    'maxLength' => '180'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type'              => PasswordType::class,
                'invalid_message'   => 'form.error.passwords_mismatch',
                'first_options'     => [
                    'label' => 'form.label.password',
                    'attr'  => ['placeholder' => 'form.label.password'],
                ],
                'second_options'    => [
                    'label' => 'form.label.password_repeat',
                    'attr'  => ['placeholder' => 'form.label.password'],
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'signup'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => RegistrationData::class]);
    }
}
