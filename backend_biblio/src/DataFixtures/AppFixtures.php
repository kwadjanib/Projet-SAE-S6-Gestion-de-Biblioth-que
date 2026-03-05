<?php

namespace App\DataFixtures;

use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Emprunt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // 1. Création de 5 Catégories
        $categories = [];
        $nomsCategories = ['Roman', 'Science-Fiction', 'Policier', 'Bande Dessinée', 'Essai'];
        foreach ($nomsCategories as $nom) {
            $cat = new Categorie();
            $cat->setNom($nom);
            $manager->persist($cat);
            $categories[] = $cat;
        }

        // 2. Création de 5 Auteurs
        $auteurs = [];
        for ($i = 1; $i <= 5; $i++) {
            $auteur = new Auteur();
            $auteur->setNom("NomAuteur$i")
                ->setPrenom("PrenomAuteur$i")
                ->setDateNaissance(new \DateTimeImmutable('1970-01-01'))
                ->setNationalite('Française');
            $manager->persist($auteur);
            $auteurs[] = $auteur;
        }

        // 3. Création de 10 Adhérents
        $adherents = [];
        for ($i = 1; $i <= 10; $i++) {
            $adherent = new Adherent();
            $email = ($i === 1) ? 'admin@biblio.fr' : "user$i@mail.fr";

            $adherent->setEmail($email)
                ->setNom("Nom$i")
                ->setPrenom("Prenom$i")
                ->setNumTel("060000000$i")
                ->setAdressePostale("$i rue de la Paix")
                ->setDateNaiss(new \DateTime('2000-01-01'))
                ->setRoles(($i === 1) ? ['ROLE_ADMIN'] : ['ROLE_USER'])
                ->setPassword($this->hasher->hashPassword($adherent, 'admin'));

            $manager->persist($adherent);
            $adherents[] = $adherent;
        }

        // 4. Création de 20 Livres
        $livres = [];
        for ($i = 1; $i <= 20; $i++) {
            $livre = new Livre();
            $livre->setTitre("Livre Titre $i")
                ->setLangue('Français')
                ->setDateSortie(new \DateTime('2022-01-01'))
                ->addAuteur($auteurs[array_rand($auteurs)])
                ->addCategory($categories[array_rand($categories)]);

            $manager->persist($livre);
            $livres[] = $livre;
        }

        // 5. Création de 15 Emprunts variés
        for ($i = 0; $i < 15; $i++) {
            $emprunt = new Emprunt();
            $emprunt->setAdherent($adherents[array_rand($adherents)])
                ->setLivre($livres[$i]) // On prend les 15 premiers livres
                ->setDateEmprunt(new \DateTime("-$i days"));

            // On simule des retours pour certains emprunts (les 5 premiers sont rendus)
            if ($i < 5) {
                $emprunt->setDateRetour(new \DateTime('now'));
            }

            $manager->persist($emprunt);
        }

        $manager->flush();
    }
}
