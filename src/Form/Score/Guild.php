<?php
namespace App\Form\Score;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Guild extends AbstractType
{
    const BLOCK_PREFIX = 'guild';
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
                    'property_path' => 'name',
                    'label' => 'Guilds',
                    'label_attr' => [
                        'class' => 'fa fa-square purple',
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
