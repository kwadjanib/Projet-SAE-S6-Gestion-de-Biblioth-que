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
        // --- CATEGORIES ---
        $categories = [];
        $categoriesData = [
            'roman' => ['Roman', 'Fiction et grands classiques.'],
            'science_fiction' => ['Science-fiction', 'Technologies, futur et voyages spatiaux.'],
            'policier' => ['Policier', 'Enquetes et suspense.'],
            'histoire' => ['Histoire', 'Recits historiques et biographies.'],
            'philosophie' => ['Philosophie', 'Idees, essais et pensee critique.'],
            'jeunesse' => ['Jeunesse', 'Lectures pour les plus jeunes.'],
            'fantastique' => ['Fantastique', 'Imaginaire et surnaturel.'],
            'aventure' => ['Aventure', 'Voyages et exploration.'],
            'dystopie' => ['Dystopie', 'Societes oppressives et futurs sombres.'],
        ];

        foreach ($categoriesData as $code => [$nom, $description]) {
            $categorie = new Categorie();
            $categorie->setNom($nom)->setDescription($description);
            $manager->persist($categorie);
            $categories[$code] = $categorie;
        }

        // --- AUTEURS ---
        $auteurs = [];
        $auteursData = [
            'hugo' => ['Hugo', 'Victor', '1802-02-26', '1885-05-22', 'Francaise'],
            'orwell' => ['Orwell', 'George', '1903-06-25', '1950-01-21', 'Britannique'],
            'asimov' => ['Asimov', 'Isaac', '1920-01-02', '1992-04-06', 'Americaine'],
            'verne' => ['Verne', 'Jules', '1828-02-08', '1905-03-24', 'Francaise'],
            'christie' => ['Christie', 'Agatha', '1890-09-15', '1976-01-12', 'Britannique'],
            'shelley' => ['Shelley', 'Mary', '1797-08-30', '1851-02-01', 'Britannique'],
            'saint_exupery' => ['Saint-Exupery', 'Antoine', '1900-06-29', '1944-07-31', 'Francaise'],
        ];

    // --- AUTEURS ---
    foreach ($auteursData as $code => $data) {
        $auteur = new Auteur();
        $auteur->setNom($data[0])
            ->setPrenom($data[1])
            ->setDateNaissance(new \DateTimeImmutable($data[2]))
            ->setDateDeces($data[3] ? new \DateTimeImmutable($data[3]) : null)
            ->setNationalite($data[4]);

        // Ajout d'une photo de profil aléatoire (visage)
        // On utilise l'index ou le nom pour varier l'image
        $auteur->setPhoto("https://i.pravatar.cc/300?u=" . urlencode($data[0]));

        $manager->persist($auteur);
        $auteurs[$code] = $auteur;
    }

        // --- LIVRES ---
        $livres = [];
        $livresData = [
            [
                'code' => 'les_miserables',
                'titre' => 'Les Miserables',
                'dateSortie' => '1862-01-01',
                'langue' => 'Francais',
                'auteurs' => ['hugo'],
                'categories' => ['roman', 'histoire'],
            ],
            [
                'code' => 'notre_dame_de_paris',
                'titre' => 'Notre-Dame de Paris',
                'dateSortie' => '1831-01-01',
                'langue' => 'Francais',
                'auteurs' => ['hugo'],
                'categories' => ['roman', 'histoire'],
            ],
            [
                'code' => '1984',
                'titre' => '1984',
                'dateSortie' => '1949-06-08',
                'langue' => 'English',
                'auteurs' => ['orwell'],
                'categories' => ['roman', 'dystopie'],
            ],
            [
                'code' => 'animal_farm',
                'titre' => 'Animal Farm',
                'dateSortie' => '1945-08-17',
                'langue' => 'English',
                'auteurs' => ['orwell'],
                'categories' => ['roman', 'dystopie'],
            ],
            [
                'code' => 'foundation',
                'titre' => 'Foundation',
                'dateSortie' => '1951-06-01',
                'langue' => 'English',
                'auteurs' => ['asimov'],
                'categories' => ['science_fiction'],
            ],
            [
                'code' => 'i_robot',
                'titre' => 'I, Robot',
                'dateSortie' => '1950-12-02',
                'langue' => 'English',
                'auteurs' => ['asimov'],
                'categories' => ['science_fiction'],
            ],
            [
                'code' => 'frankenstein',
                'titre' => 'Frankenstein',
                'dateSortie' => '1818-01-01',
                'langue' => 'English',
                'auteurs' => ['shelley'],
                'categories' => ['fantastique', 'science_fiction'],
            ],
            [
                'code' => 'orient_express',
                'titre' => 'Murder on the Orient Express',
                'dateSortie' => '1934-01-01',
                'langue' => 'English',
                'auteurs' => ['christie'],
                'categories' => ['policier'],
            ],
            [
                'code' => 'petit_prince',
                'titre' => 'Le Petit Prince',
                'dateSortie' => '1943-04-06',
                'langue' => 'Francais',
                'auteurs' => ['saint_exupery'],
                'categories' => ['jeunesse', 'fantastique'],
            ],
            [
                'code' => 'voyage_centre_terre',
                'titre' => 'Voyage au centre de la Terre',
                'dateSortie' => '1864-11-25',
                'langue' => 'Francais',
                'auteurs' => ['verne'],
                'categories' => ['aventure', 'science_fiction'],
            ],
        ];

        // --- LIVRES ---
        foreach ($livresData as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre'])
                ->setDateSortie(new \DateTime($data['dateSortie']))
                ->setLangue($data['langue']);

            // Ajout d'une image de couverture aléatoire liée aux livres
            $livre->setPhotoCouverture("https://loremflickr.com/320/480/book,library?lock=" . rand(1, 1000));

            foreach ($data['auteurs'] as $auteurCode) {
                $livre->addAuteur($auteurs[$auteurCode]);
            }
            foreach ($data['categories'] as $categorieCode) {
                $livre->addCategory($categories[$categorieCode]);
            }

            $manager->persist($livre);
            $livres[$data['code']] = $livre;
        }

        // --- UTILISATEURS ---
        $utilisateurs = [];

        $admin = new Utilisateur();
        $admin->setEmail('admin@biblio.fr')
            ->setNom('Admin')
            ->setPrenom('Principal')
            ->setRoles(['ROLE_ADMIN', 'ROLE_RESPONABLE', 'ROLE_BIBLIOTHECAIRE', 'ROLE_ADHERENT'])
            ->setDateAdhesion(new \DateTime('2024-09-01'))
            ->setDateNaiss(new \DateTime('1985-03-10'))
            ->setAdressePostale('10 rue de l Administration, 31000 Toulouse')
            ->setNumTel('0102030405')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);
        $utilisateurs['admin'] = $admin;

        $usersData = [
            [
                'code' => 'alice',
                'email' => 'alice.dupont@biblio.fr',
                'nom' => 'Dupont',
                'prenom' => 'Alice',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-01-15',
                'dateNaiss' => '1998-05-12',
                'adresse' => '12 rue Victor Hugo, 31000 Toulouse',
                'numTel' => '0612345678',
                'password' => 'password',
            ],
            [
                'code' => 'ben',
                'email' => 'ben.leclerc@biblio.fr',
                'nom' => 'Leclerc',
                'prenom' => 'Ben',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-03-05',
                'dateNaiss' => '1995-11-02',
                'adresse' => '8 avenue des Arts, 31700 Blagnac',
                'numTel' => '0623456789',
                'password' => 'password',
            ],
            [
                'code' => 'carla',
                'email' => 'carla.moreau@biblio.fr',
                'nom' => 'Moreau',
                'prenom' => 'Carla',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-06-18',
                'dateNaiss' => '2001-09-22',
                'adresse' => '5 boulevard des Sciences, 31000 Toulouse',
                'numTel' => '0634567890',
                'password' => 'password',
            ],
            [
                'code' => 'david',
                'email' => 'david.renard@biblio.fr',
                'nom' => 'Renard',
                'prenom' => 'David',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-09-30',
                'dateNaiss' => '1992-07-08',
                'adresse' => '44 rue de la Paix, 31000 Toulouse',
                'numTel' => '0645678901',
                'password' => 'password',
            ],
        ];

        foreach ($usersData as $data) {
            $user = new Utilisateur();
            $user->setEmail($data['email'])
                ->setNom($data['nom'])
                ->setPrenom($data['prenom'])
                ->setRoles($data['roles'])
                ->setDateAdhesion(new \DateTime($data['dateAdhesion']))
                ->setDateNaiss(new \DateTime($data['dateNaiss']))
                ->setAdressePostale($data['adresse'])
                ->setNumTel($data['numTel'])
                ->setPassword($this->hasher->hashPassword($user, $data['password']));
            $manager->persist($user);
            $utilisateurs[$data['code']] = $user;
        }

        // --- EMPRUNTS ---
        $empruntsData = [
            [
                'user' => 'alice',
                'livre' => '1984',
                'dateEmprunt' => '2026-03-01',
                'dateRetour' => null,
            ],
            [
                'user' => 'ben',
                'livre' => 'petit_prince',
                'dateEmprunt' => '2026-02-10',
                'dateRetour' => '2026-02-24',
            ],
        ];

        foreach ($empruntsData as $data) {
            $emprunt = new Emprunt();
            $emprunt->setDateEmprunt(new \DateTime($data['dateEmprunt']))
                ->setDateRetour($data['dateRetour'] ? new \DateTime($data['dateRetour']) : null)
                ->setUtilisateur($utilisateurs[$data['user']])
                ->setLivre($livres[$data['livre']]);
            $manager->persist($emprunt);

            if ($data['dateRetour'] === null) {
                $livres[$data['livre']]->setEmprunt($emprunt);
            }
        }

        // --- RESERVATIONS ---
        $reservationsData = [
            [
                'user' => 'carla',
                'livre' => 'foundation',
                'dateResa' => '2026-03-05',
            ],
            [
                'user' => 'david',
                'livre' => 'voyage_centre_terre',
                'dateResa' => '2026-03-07',
            ],
        ];

        foreach ($reservationsData as $data) {
            $reservation = new Reservations();
            $reservation->setDateResa(new \DateTime($data['dateResa']))
                ->setUtilisateur($utilisateurs[$data['user']])
                ->setLivre($livres[$data['livre']]);
            $manager->persist($reservation);
        }

        $manager->flush();
    }
}
