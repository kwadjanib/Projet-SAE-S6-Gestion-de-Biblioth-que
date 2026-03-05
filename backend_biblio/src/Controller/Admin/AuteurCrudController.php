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

            // Ce champ est OBLIGATOIRE pour éviter l'erreur SQL 500
            DateField::new('dateNaissance', 'Date de naissance'),

            // Champs optionnels selon ton entité et ton schéma
            DateField::new('dateDeces', 'Date de décès')->setRequired(false),
            TextField::new('nationalite', 'Nationalité'),

            // Utilisation de TextEditorField pour la description (comme dans ton schéma)
            TextEditorField::new('description', 'Biographie'),

            // Configuration de l'image si tu souhaites gérer les photos d'auteurs
            ImageField::new('photo', 'Photo de l\'auteur')
                ->setBasePath('uploads/auteurs')
                ->setUploadDir('public/uploads/auteurs')
                ->setRequired(false),

        ];
    }
}
