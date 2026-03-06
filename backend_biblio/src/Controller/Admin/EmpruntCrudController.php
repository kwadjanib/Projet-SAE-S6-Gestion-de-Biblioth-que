<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class EmpruntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('adherent')->setFormTypeOption('placeholder', 'Choisir un adhérent'),
            AssociationField::new('livre')->setFormTypeOption('placeholder', 'Choisir un livre'),
            DateField::new('dateEmprunt'),
            DateField::new('dateRetour', 'Date de retour effective')->setRequired(false)

        ];
    }
}
