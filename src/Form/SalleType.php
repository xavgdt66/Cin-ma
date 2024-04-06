<?php
// src/Form/SalleType.php
namespace App\Form;

use App\Entity\Salle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalleType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('nombrePlaces', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => Salle::class,
        ]);
    }
}
