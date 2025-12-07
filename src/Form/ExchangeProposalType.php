<?php

namespace App\Form;

use App\Entity\ExchangeProposal;
use App\Entity\Skill;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ExchangeProposalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('offeredSkill', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'title', // <--- use title, not name
                'placeholder' => 'Select a skill to offer',
            ])
            ->add('requestedSkill', EntityType::class, [
                'class' => Skill::class,
                'choice_label' => 'title', // <--- use title
                'placeholder' => 'Select a skill you want to learn',
            ])

            ->add('proposal', TextType::class, [
                'required' => false,
                'label' => 'Additional notes',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExchangeProposal::class,
        ]);
    }

}
