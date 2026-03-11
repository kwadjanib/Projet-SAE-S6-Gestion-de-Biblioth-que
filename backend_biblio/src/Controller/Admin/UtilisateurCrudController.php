<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextField::new('prenom'),
            EmailField::new('email'),
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                ->setRequired($pageName === 'new')
                ->setFormTypeOption('mapped', false)
                ->setHelp('Laisser vide pour ne pas modifier le mot de passe')
                ->hideOnIndex()
                ->hideOnDetail(),
            DateField::new('dateNaiss', 'Date de naissance'),
            DateField::new('dateAdhesion', 'Date d\'adhésion')->hideOnForm(),
            TextField::new('adressePostale', 'Adresse'),
            TextField::new('numTel', 'Téléphone'),
            ImageField::new('photo')
                ->setBasePath('uploads/adherents')
                ->setUploadDir('public/uploads/adherents')
                ->setRequired(false),
            ChoiceField::new('roles')
                ->setChoices([
                    'Responsable_Biblio' => 'ROLE_RESPONSABLE',
                    'Bibliothécaire' => 'ROLE_BIBLIOTHECAIRE',
                    'Adhérent' => 'ROLE_ADHERENT'
                ])
                ->allowMultipleChoices()
                ->renderExpanded(),
            BooleanField::new('enabled', 'Compte Actif')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashUserPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->hashUserPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function hashUserPassword(Utilisateur $user): void
    {
        $password = $this->getContext()->getRequest()->request->all('Utilisateur')['password'] ?? null;

        if (!empty($password)) {
            $hashed = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashed);
        }
    }
}
