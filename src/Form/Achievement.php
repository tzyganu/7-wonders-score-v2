<?php
namespace App\Form;

use App\Entity\AchievementColor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use App\Entity\AchievementGroup;

class Achievement extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class)
            ->add('name')
            ->add('achievement_color', EntityType::class, [
                'class' => AchievementColor::class,
                'choice_label' => 'name',
            ])
            ->add('group', EntityType::class, [
                'class' => AchievementGroup::class,
                'choice_label' => 'name',
            ])
            ->add('description', TextareaType::class)
            ->add('identifier')
        ;
    }
}
