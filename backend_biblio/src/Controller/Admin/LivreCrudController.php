<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        TextField::new('titre', 'Titre du livre'),
        DateField::new('dateSortie', 'Date de sortie'),
        TextField::new('langue', 'Langue'),
        ImageField::new('photoCouverture')
            ->setBasePath('uploads/couvertures')
            ->setUploadDir('public/uploads/couvertures')
            ->setRequired(false),
        AssociationField::new('auteurs', 'Auteurs'),
        AssociationField::new('categories', 'Catégories'),
    ];
}
}
