<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Auteur;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
     public function __construct(private EntityManagerInterface $em) {}

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'nbLivres' => $this->em->getRepository(Livre::class)->count([]),
            'nbAdherents' => $this->em->getRepository(Adherent::class)->count([]),
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
        yield MenuItem::linkToCrud('Adhérents', 'fas fa-users', Adherent::class);
        yield MenuItem::linkToCrud('Emprunts', 'fas fa-exchange-alt', Emprunt::class);
        yield MenuItem::linkToCrud('Utilisateurs (Personnel)', 'fas fa-user-shield', Utilisateur::class);
        if ($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::section('Catalogue');
            yield MenuItem::linkToCrud('Livres', 'fas fa-book', Livre::class);
            yield MenuItem::linkToCrud('Auteurs', 'fas fa-user-tie', Auteur::class);
            yield MenuItem::linkToCrud('Catégories', 'fas fa-tags', Categorie::class);
        }
    }
}
