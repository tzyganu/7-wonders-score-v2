<?php
namespace App\Form\Score;

use App\Score\Calculator\Cash as CashCalculator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Cash extends AbstractType
{
    const BLOCK_PREFIX = 'cash';
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
                    'label' => 'Cash',
                    'label_attr' => [
                        'class' => 'fa fa-money'
                    ]
                ]
            )
            ->add(
                CashCalculator::COINS,
                NumberType::class,
                [
                    'property_path' => 'name',
                    'label' => '€',
                    'label_attr' => [
                        'class' => 'input-group-addon'
                    ]
                ]
            )
            ->add(
                CashCalculator::MINUS_ONE,
                NumberType::class,
                [
                    'label' => '-1',
                    'label_attr' => [
                        'class' => 'input-group-addon'
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
