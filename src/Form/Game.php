<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Game extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('played_on', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('cities', CheckboxType::class, ['required' => false])
            ->add('leaders', CheckboxType::class, ['required' => false])
            ->add('playLeft', CheckboxType::class, ['required' => false])
            ->add('canExclude', CheckboxType::class, ['required' => false, 'label' => 'Can Exclude from reports']);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'game';
    }
}
