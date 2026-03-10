<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Reservations;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- UTILISATEURS (DateTime) ---
        $admin = new Utilisateur();
        $admin->setEmail('admin@biblio.fr')
            ->setNom('ADMIN')
            ->setPrenom('Principal')
            ->setRoles(['ROLE_ADMIN', 'ROLE_RESPONSABLE'])
            ->setDateAdhesion(new \DateTime('-1 year'))
            ->setDateNaiss(new \DateTime('1990-01-01'))
            ->setAdressePostale('10 rue de l\'Administration, 31000 Toulouse')
            ->setNumTel('0123456789')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $utilisateurs = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = new Utilisateur();
            $user->setEmail("user$i@gmail.com")
                ->setNom("Nom$i")
                ->setPrenom("Prenom$i")
                ->setRoles(['ROLE_USER'])
                ->setDateAdhesion(new \DateTime("-" . rand(1, 10) . " months"))
                ->setDateNaiss(new \DateTime('1995-05-15'))
                ->setAdressePostale("$i rue des Lecteurs, 31700 Blagnac")
                ->setNumTel('060000000' . $i)
                ->setPassword($this->hasher->hashPassword($user, 'user'));
            $manager->persist($user);
            $utilisateurs[] = $user;
        }

        // --- CATEGORIES ---
        $categoriesLabel = ['Roman', 'Science-Fiction', 'Policier', 'Biographie', 'Bande Dessinée'];
        $categoriesEntities = [];
        foreach ($categoriesLabel as $nom) {
            $cat = new Categorie();
            $cat->setNom($nom)
                ->setDescription("Livres de type $nom");
            $manager->persist($cat);
            $categoriesEntities[] = $cat;
        }

        // --- AUTEURS (DateTimeImmutable) ---
        $auteursData = [
            ['Hugo', 'Victor', '1802-02-26'],
            ['Orwell', 'George', '1903-06-25'],
            ['Asimov', 'Isaac', '1920-01-02'],
        ];
        $auteursEntities = [];
        foreach ($auteursData as $data) {
            $auteur = new Auteur();
            $auteur->setNom($data[0])
                ->setPrenom($data[1])
                ->setDateNaissance(new \DateTimeImmutable($data[2]))
                ->setNationalite('Française');
            $manager->persist($auteur);
            $auteursEntities[] = $auteur;
        }

        // --- LIVRES (DateTime) ---
        $livresEntities = [];
        for ($i = 1; $i <= 10; $i++) {
            $livre = new Livre();
            $livre->setTitre("Livre Titre $i")
                ->setDateSortie(new \DateTime("-" . rand(1, 20) . " years"))
                ->setLangue('Français')
                ->addAuteur($auteursEntities[array_rand($auteursEntities)])
                ->addCategory($categoriesEntities[array_rand($categoriesEntities)]); // Corrigé : addCategory

            $manager->persist($livre);
            $livresEntities[] = $livre;
        }

        // --- EMPRUNTS (DateTime) ---
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(new \DateTime('-10 days'))
            ->setUtilisateur($utilisateurs[0])
            ->setLivre($livresEntities[0]);
        $manager->persist($emprunt);

        $livresEntities[0]->setEmprunt($emprunt);

        // --- RESERVATIONS (DateTime) ---
        $resa = new Reservations();
        $resa->setDateResa(new \DateTime('now'))
            ->setUtilisateur($utilisateurs[1])
            ->setLivre($livresEntities[2]);
        $manager->persist($resa);

        $manager->flush();
    }
}
