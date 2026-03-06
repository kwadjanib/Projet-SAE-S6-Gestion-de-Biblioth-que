<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom', 'Nom de l\'auteur'),
            TextField::new('prenom', 'Prénom'),
            DateField::new('dateNaissance', 'Date de naissance'),
            DateField::new('dateDeces', 'Date de décès')->setRequired(false),
            TextField::new('nationalite', 'Nationalité'),
            TextEditorField::new('description', 'Biographie'),
            ImageField::new('photo', 'Photo de l\'auteur')
                ->setBasePath('uploads/auteurs')
                ->setUploadDir('public/uploads/auteurs')
                ->setRequired(false),

        ];
    }
}
