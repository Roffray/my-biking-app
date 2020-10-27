<?php

namespace App\Form\User;

use App\User\ProfileData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
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
                'attr'  => ['placeholder' => 'form.label.email', 'maxLength' => '180']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ProfileData::class]);
    }
}
