<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Trade extends AbstractType
{
    const BLOCK_PREFIX = 'trade';
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
                    'label' => 'Trade',
                    'label_attr' => [
                        'class' => 'fa fa-square yellow',
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
