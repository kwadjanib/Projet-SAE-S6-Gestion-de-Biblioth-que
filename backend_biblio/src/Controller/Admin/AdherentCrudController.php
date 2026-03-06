<?php

namespace App\Controller\Admin;

use App\Entity\Adherent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class AdherentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Adherent::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            DateField::new('dateNaiss', 'Date de naissance'),
            DateField::new('dateAdhesion', 'Date d\'adhésion')->hideOnForm(),
            TextField::new('adressePostale', 'Adresse'),
            TextField::new('numTel', 'Téléphone'),
            ImageField::new('photo')
                ->setBasePath('uploads/adherents')
                ->setUploadDir('public/uploads/adherents')
                ->setRequired(false),
        ];
    }
}
