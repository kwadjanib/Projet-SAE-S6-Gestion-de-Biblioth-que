<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
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
        // Categories
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
            'informatique' => ['Informatique', 'Developpement et qualite logicielle.'],
        ];

        foreach ($categoriesData as $code => [$nom, $description]) {
            $categorie = new Categorie();
            $categorie->setNom($nom)->setDescription($description);
            $manager->persist($categorie);
            $categories[$code] = $categorie;
        }

        // Auteurs
        $auteurs = [];
        $auteursData = [
            'hugo' => ['Hugo', 'Victor', '1802-02-26', '1885-05-22', 'Francaise'],
            'orwell' => ['Orwell', 'George', '1903-06-25', '1950-01-21', 'Britannique'],
            'asimov' => ['Asimov', 'Isaac', '1920-01-02', '1992-04-06', 'Americaine'],
            'verne' => ['Verne', 'Jules', '1828-02-08', '1905-03-24', 'Francaise'],
            'christie' => ['Christie', 'Agatha', '1890-09-15', '1976-01-12', 'Britannique'],
            'shelley' => ['Shelley', 'Mary', '1797-08-30', '1851-02-01', 'Britannique'],
            'saint_exupery' => ['Saint-Exupery', 'Antoine', '1900-06-29', '1944-07-31', 'Francaise'],
            'herbert' => ['Herbert', 'Frank', '1920-10-08', '1986-02-11', 'Americaine'],
            'harari' => ['Harari', 'Yuval', '1976-02-24', null, 'Israelienne'],
            'martin' => ['Martin', 'Robert', '1952-12-05', null, 'Americaine'],
        ];

        foreach ($auteursData as $code => $data) {
            $auteur = new Auteur();
            $auteur->setNom($data[0])
                ->setPrenom($data[1])
                ->setDateNaissance(new \DateTimeImmutable($data[2]))
                ->setDateDeces($data[3] ? new \DateTimeImmutable($data[3]) : null)
                ->setNationalite($data[4]);
            $manager->persist($auteur);
            $auteurs[$code] = $auteur;
        }

        // Livres
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
            [
                'code' => 'dune',
                'titre' => 'Dune',
                'dateSortie' => '1965-08-01',
                'langue' => 'English',
                'auteurs' => ['herbert'],
                'categories' => ['science_fiction'],
            ],
            [
                'code' => 'sapiens',
                'titre' => 'Sapiens',
                'dateSortie' => '2011-01-01',
                'langue' => 'English',
                'auteurs' => ['harari'],
                'categories' => ['histoire'],
            ],
            [
                'code' => 'clean_code',
                'titre' => 'Clean Code',
                'dateSortie' => '2008-08-01',
                'langue' => 'English',
                'auteurs' => ['martin'],
                'categories' => ['informatique'],
            ],
        ];

        foreach ($livresData as $data) {
            $livre = new Livre();
            $livre->setTitre($data['titre'])
                ->setDateSortie(new \DateTime($data['dateSortie']))
                ->setLangue($data['langue']);

            foreach ($data['auteurs'] as $auteurCode) {
                $livre->addAuteur($auteurs[$auteurCode]);
            }
            foreach ($data['categories'] as $categorieCode) {
                $livre->addCategory($categories[$categorieCode]);
            }

            $manager->persist($livre);
        }

        // Utilisateurs
        $admin = new Utilisateur();
        $admin->setEmail('admin@biblio.fr')
            ->setNom('Admin')
            ->setPrenom('Principal')
            ->setRoles(['ROLE_ADMIN', 'ROLE_RESPONSABLE', 'ROLE_BIBLIOTHECAIRE', 'ROLE_ADHERENT'])
            ->setDateAdhesion(new \DateTime('2024-09-01'))
            ->setDateNaiss(new \DateTime('1985-03-10'))
            ->setAdressePostale('10 rue de l Administration, 31000 Toulouse')
            ->setNumTel('0102030405')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $manager->persist($admin);

        $usersData = [
            [
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
                'email' => 'anthony.durand@biblio.fr',
                'nom' => 'Durand',
                'prenom' => 'Anthony',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-02-10',
                'dateNaiss' => '1996-01-20',
                'adresse' => '5 rue des Lilas, 31000 Toulouse',
                'numTel' => '0612340001',
                'password' => 'password',
            ],
            [
                'email' => 'andre.lefevre@biblio.fr',
                'nom' => 'Lefevre',
                'prenom' => 'Andre',
                'roles' => ['ROLE_ADHERENT'],
                'dateAdhesion' => '2025-02-12',
                'dateNaiss' => '1994-09-02',
                'adresse' => '18 avenue des Arts, 31000 Toulouse',
                'numTel' => '0612340002',
                'password' => 'password',
            ],
            [
                'email' => 'bruno.martin@biblio.fr',
                'nom' => 'Martin',
                'prenom' => 'Bruno',
                'roles' => ['ROLE_BIBLIOTHECAIRE'],
                'dateAdhesion' => '2024-11-02',
                'dateNaiss' => '1988-06-14',
                'adresse' => '2 place du Capitole, 31000 Toulouse',
                'numTel' => '0612340003',
                'password' => 'password',
            ],
            [
                'email' => 'robert.bertrand@biblio.fr',
                'nom' => 'Bertrand',
                'prenom' => 'Robert',
                'roles' => ['ROLE_RESPONSABLE'],
                'dateAdhesion' => '2024-10-10',
                'dateNaiss' => '1980-03-22',
                'adresse' => '40 boulevard Gambetta, 31000 Toulouse',
                'numTel' => '0612340004',
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
        }

        $manager->flush();
    }
}
