<?php
namespace App\Form\Report;

use App\Entity\Wonder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class Player extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_from', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('date_to', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'required' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('player', EntityType::class, [
                'class' => \App\Entity\Player::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'select-all'],
            ])
            ->add('group_by_player', CheckboxType::class, [
                'required' => false,
            ])
            ->add('wonder', EntityType::class, [
                'class' => Wonder::class,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'select-all'],
            ])
            ->add('group_by_wonder', CheckboxType::class, [
                'required' => false,
            ])
            ->add('side', ChoiceType::class, [
                'choices' => ['A' => 'A', 'B' => 'B' ],
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'select-all'],
            ])
            ->add('group_by_side', CheckboxType::class, [
                'required' => false,
            ])
            ->add('player_count', ChoiceType::class, [
                'choices' => array_combine(range(3, 8), range(3,8)),
                'required' => false,
                'multiple' => true,
                'attr' => ['class' => 'select-all'],
            ])
            ->add('group_by_player_count', CheckboxType::class, [
                'required' => false,
            ])
        ;
    }

    public function getBlockPrefix()
    {
        return 'report';
    }

}
