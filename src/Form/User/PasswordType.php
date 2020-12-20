<?php

namespace App\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType as Password;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type'              => Password::class,
                'invalid_message'   => 'The passwords must match.',
                'first_options'     => [
                    'label' => 'New password',
                    'attr'  => ['placeholder' => 'Your password']
                ],
                'second_options'    => [
                    'label' => 'Repeat your new password',
                    'attr'  => ['placeholder' => 'Repeat your password']
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProfileData::class]);
    }
}