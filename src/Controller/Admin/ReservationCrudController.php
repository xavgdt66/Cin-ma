<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Entity\Salle;
use App\Entity\DateDiffusion;
use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;  // Pour utiliser les entity jointer 

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('movie')->formatValue(function ($value, $entity) {
                return $entity->getMovie()->getTitre();
            }),
           
            AssociationField::new('user'),
            IntegerField::new('number_of_seats', 'Nombre de sieges réserver ')
        ];
    }
}
