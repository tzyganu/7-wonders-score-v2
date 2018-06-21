<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Wonder extends AbstractType
{
    const BLOCK_PREFIX = 'wonder';
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
                        'class' => 'category-score'
                    ],
                    'label' => 'Wonder',
                    'label_attr' => [
                        'class' => 'fa fa-signal yellow',
                    ],
                    'property_path' => 'name'
                ]
            )
            ->add('stage', NumberType::class, ['label' => 'Levels']);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return self::BLOCK_PREFIX;
    }
}
