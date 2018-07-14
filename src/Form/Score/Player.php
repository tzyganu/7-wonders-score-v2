<?php
namespace App\Form\Score;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Player extends AbstractType
{
    const BLOCK_PREFIX = 'player';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                "player_id",
                EntityType::class,
                [
                    'class' => \App\Entity\Player::class,
                    'choice_label' => 'name',
                    'label' => 'Select Player',
                    'required' => false,
                    'attr' => [
                        'class' => 'player-select',
                    ],
                    'label_attr' => [
                        'class' => 'game-player'
                    ]
                ]
            )
            ->add(
                "wonder_id",
                \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
                [
                    'label' => 'Wonder',
                    'label_attr' => [
                        'class' => 'block-label'
                    ],
                    'attr' => [
                        'class' => 'wonder-select',
                        'required' => 'required',
                    ]
                ]
            )
            ->add(
                "side",
                \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,
                [
                    'choices' => [
                        '' => '', 'A' => 'A', 'B' => 'B'
                    ],
                    'attr' => [
                        'class' => 'wonder-side'
                    ],
                    'label_attr' => [
                        'class' => 'block-label'
                    ],
                ]
            )
        ;
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return self::BLOCK_PREFIX;
    }
}
