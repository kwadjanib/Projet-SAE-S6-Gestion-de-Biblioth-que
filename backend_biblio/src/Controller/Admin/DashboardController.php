<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private EntityManagerInterface $em) {}

    public function index(): Response
    {
        $empruntsEnCours = $this->em->getRepository(Emprunt::class)->findBy(['dateRetour' => null]);
        $nbRetards = 0;
        $empruntsFinis = $this->em->createQuery(
            'SELECT c.nom as nom, COUNT(l.id) as nb 
         FROM App\Entity\Emprunt e 
         JOIN e.livre l 
         JOIN l.categories c 
         WHERE e.dateRetour IS NOT NULL 
         GROUP BY c.nom'
        )->getResult();
        $empruntsFinisAuteurs = $this->em->createQuery(
            'SELECT CONCAT(a.prenom, \' \', a.nom) as nom, COUNT(l.id) as nb 
         FROM App\Entity\Emprunt e 
         JOIN e.livre l 
         JOIN l.auteurs a 
         WHERE e.dateRetour IS NOT NULL 
         GROUP BY a.id'
        )->getResult();
        foreach ($empruntsEnCours as $emprunt) {
            if ($emprunt->estEnRetard()) {
                $nbRetards++;
            }
        }
        return $this->render('admin/dashboard.html.twig', [
            'nbLivres' => $this->em->getRepository(Livre::class)->count([]),
            'nbAdherents' => $this->em->getRepository(Utilisateur::class)->count([]),
            'nbEmpruntsEnCours' => $this->em->getRepository(Emprunt::class)->count(['dateRetour' => null]),
            'nbEmpruntsEnRetard' => $nbRetards,
            'empruntsFinis' => $empruntsFinis,
            'empruntsFinisAuteurs' => $empruntsFinisAuteurs,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('Bibliothèque - Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home');

        yield MenuItem::section('Gestion');

        // On remplace le lien Adherent par Utilisateur
        yield MenuItem::linkToCrud('Membres & Adhérents', 'fas fa-users', Utilisateur::class);
        yield MenuItem::linkToCrud('Emprunts', 'fas fa-exchange-alt', Emprunt::class);

        if ($this->isGranted('ROLE_RESPONSABLE')) {
            yield MenuItem::section('Catalogue');
            yield MenuItem::linkToCrud('Livres', 'fas fa-book', Livre::class);
            yield MenuItem::linkToCrud('Auteurs', 'fas fa-user-tie', Auteur::class);
            yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);
        }
    }
}
