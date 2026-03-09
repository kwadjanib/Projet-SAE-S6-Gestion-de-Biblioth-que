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
        // On compte désormais les utilisateurs qui ont le rôle ROLE_ADHERENT
        // Si vous n'utilisez pas de filtre spécifique, count([]) comptera tout le personnel et les adhérents
        return $this->render('admin/dashboard.html.twig', [
            'nbLivres' => $this->em->getRepository(Livre::class)->count([]),
            'nbAdherents' => $this->em->getRepository(Utilisateur::class)->count([]), 
            'nbEmpruntsEnCours' => $this->em->getRepository(Emprunt::class)->count(['dateRetour' => null]),
            'nbEmpruntsEnRetard' => 0,
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

        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Catalogue');
            yield MenuItem::linkToCrud('Livres', 'fas fa-book', Livre::class);
            yield MenuItem::linkToCrud('Auteurs', 'fas fa-user-tie', Auteur::class);
            yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);
        }
    }
}