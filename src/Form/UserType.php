<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('adress', TextType::class)
            ->add('bio', TextareaType::class, [
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'mapped' => false, // On gère le fichier uploadé manuellement
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Actif' => true,
                    'Inactif' => false,
                ],
                'expanded' => false, // dropdown select
                'multiple' => false,
                'label' => 'Statut',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('offeredSkill', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ])
            ->add('requestedSkill', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
            ]);


        // roles et createdAt sont généralement gérés automatiquement et ne sont pas dans le formulaire utilisateur
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
