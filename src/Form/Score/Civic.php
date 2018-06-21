<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Civic extends AbstractType
{
    const BLOCK_PREFIX = 'civic';
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'score',
                NumberType::class,
                [
                    'attr' => [
                        'class' => 'category-score'
                    ],
                    'property_path' => 'name',
                    'label' => 'Civic',
                    'label_attr' => [
                        'class' => 'fa fa-square blue',
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
