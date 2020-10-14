<?php

namespace App\Form;

use App\Contact\ContactData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'form.label.firstname'
            ])
            ->add('email', EmailType::class, [
                'label' => 'form.label.email'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'form.label.message'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.label.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'    => ContactData::class
        ]);
    }
}