<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use App\Entity\Salle;
use App\Entity\DateDiffusion;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;  // Pour utiliser les entity jointer 

class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('titre'),
            TextField::new('description'),
            AssociationField::new('salles'),
            AssociationField::new('dateDiffusions'),
            AssociationField::new('user'),

        ];
    }
    
}
