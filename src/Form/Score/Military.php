<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Military extends AbstractType
{
    const BLOCK_PREFIX = 'military';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'score',
                null,
                [
                    'label' => 'Military',
                    'attr' => [
                        'disabled' => true,
                        'class' => 'category-score'
                    ],
                    'property_path' => 'name',
                    'label_attr' => [
                        'class' => 'fa fa-shield red',
                    ]
                ]
            )
            ->add(
                \App\Score\Calculator\Military::FIVE,
                NumberType::class,
                [
                    'label' => 5,
                    'label_attr' => [
                        'class' => 'input-group-addon'
                    ],
                ]
            )
            ->add(
                \App\Score\Calculator\Military::THREE,
                NumberType::class,
                [
                    'label' => 3,
                    'label_attr' => [
                        'class' => 'input-group-addon'
                    ],
                ]
            )
            ->add(
                \App\Score\Calculator\Military::ONE,
                NumberType::class,
                [
                    'label' => 1,
                    'label_attr' => [
                        'class' => 'input-group-addon'
                    ],
                ]
            )
            ->add(
                \App\Score\Calculator\Military::MINUS_ONE,
                NumberType::class, [
                    'label' => -1,
                    'label_attr' => [
                        'class' => 'input-group-addon',
                        'title' => 'Number of -1 military tokens'
                    ],
                    'attr' => ['title' => 'Number of -1 military tokens']
                ]
            )->add(
                'shield',
                NumberType::class, [
//                    'label' => '☗',
                    'label' => 'Number of shields',
                    'label_attr' => [
                        'class' => 'input-group-addon',
                        'title' => 'Number of shields',
                        'data-icon' => 'fa fa-shield'
                    ],
                    'attr' => ['title' => 'Number of shields']
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
