<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Emprunt;
use App\Entity\Reservations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            AssociationField::new('utilisateur', 'Adhérent')->setFormTypeOption('placeholder', 'Choisir un adhérent'),
            AssociationField::new('livre')->setFormTypeOption('placeholder', 'Choisir un livre'),
            DateField::new('dateEmprunt'),
            DateField::new('dateRetour', 'Date de retour effective')->setRequired(false),
            TextField::new('statut', 'Statut')
                ->onlyOnIndex(),
        ];
    }
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof Emprunt) {
            $livre = $entityInstance->getLivre();
            $user = $entityInstance->getUtilisateur();
            $reservation = $entityManager->getRepository(Reservations::class)->findOneBy(['livre' => $livre]);

            if ($reservation && $reservation->getUtilisateur() !== $user) {
                throw new \Exception("Ce livre est réservé par un autre adhérent !");
            }

            $empruntsActifs = $user->getEmprunts()->filter(function ($e) {
                return $e->getDateRetour() === null;
            });
            if (count($empruntsActifs) >= 5) {
                $this->addFlash('danger', 'Action impossible : Cet adhérent a déjà atteint la limite de 5 emprunts en cours !');
                return;
            }
            if ($reservation && $reservation->getUtilisateur() === $user) {
                $entityManager->remove($reservation);
            }
            if (!$entityInstance->getDateEmprunt()) {
                $entityInstance->setDateEmprunt(new \DateTime());
            }

            $empruntEnCours = $entityManager->getRepository(Emprunt::class)->findOneBy([
                'livre' => $livre,
                'dateRetour' => null
            ]);

            if ($empruntEnCours) {
                $this->addFlash('danger', sprintf(
                    'Action impossible : Le livre "%s" est déjà emprunté par %s %s !',
                    $livre->getTitre(),
                    $empruntEnCours->getUtilisateur()->getPrenom(),
                    $empruntEnCours->getUtilisateur()->getNom()
                ));
                return; // On arrête tout ici, on n'enregistre pas le doublon
            }
            parent::persistEntity($entityManager, $entityInstance);
        }
    }
}
