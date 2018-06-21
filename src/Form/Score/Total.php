<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Total extends AbstractType
{
    const BLOCK_PREFIX = 'total';
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
                        'class' => 'grand-total'
                    ],
                    'property_path' => 'name',
                    'label_attr' => [
                        'class' => 'fa fa-plus'
                    ]
                ]
            );
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return self::BLOCK_PREFIX;
    }
}
