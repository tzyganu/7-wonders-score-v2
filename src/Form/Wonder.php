<?php
namespace App\Form;

use App\Entity\WonderSet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class Wonder extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('name')
            ->add('description', TextareaType::class, ['required' => false])
            ->add('wonder_set', EntityType::class, [
                'class' => WonderSet::class,
                'choice_label' => 'name',
            ])
            ->add('playable_with_leaders', CheckboxType::class, ['required' => false])
            ->add('playable_without_leaders', CheckboxType::class, ['required' => false])
            ->add('playable_with_cities', CheckboxType::class, ['required' => false])
            ->add('playable_without_cities', CheckboxType::class, ['required' => false])
            ->add('official', CheckboxType::class, ['required' => false])
        ;
    }
}
