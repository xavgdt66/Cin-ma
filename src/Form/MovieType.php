<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\DateDiffusion; 
use App\Form\DateDiffusionType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('dateDiffusions', CollectionType::class, [// Systeme de form imbriquer qui permet d'ajoute Le form DateDiffusionType
                'entry_type' => DateDiffusionType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('salles', EntityType::class, [
                'class' => 'App\Entity\Salle',
                'choices' => $options['salles'],
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
            'salles' => [],

        ]);
    }
}
