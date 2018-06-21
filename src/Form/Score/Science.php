<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Science extends AbstractType
{
    const BLOCK_PREFIX = 'science';
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
                    'attr' => [
                        'disabled' => true,
                        'class' => 'category-score'
                    ],
                    'property_path' => 'name',
                    'label' => 'Science',
                    'label_attr' => [
                        'class' => 'fa fa-flask green',
                    ]
                ]
            )
            ->add(
                'gear',
                NumberType::class,
                [
                    'property_path' => 'name',
//                    'label' => '⚙',
                    'label' => 'Gears',
                    'label_attr' => [
                        'class' => 'input-group-addon',
                        'data-icon' => 'fa fa-gear',
                        'title' => 'Number of Gears'
                    ]
                ]
            )
            ->add(
                'compass',
                NumberType::class,
                [
                    'label' => 'Λ',
                    'label_attr' => [
                        'class' => 'input-group-addon'
                    ]
                ]
            )
            ->add(
                'tablet',
                NumberType::class,
                [
//                    'label' => '□',
                    'label' => 'Tablets',
                    'label_attr' => [
                        'class' => 'input-group-addon',
                        'data-icon' => 'fa fa-tablet',
                        'title' => 'Number of Tablets'
                    ]
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
