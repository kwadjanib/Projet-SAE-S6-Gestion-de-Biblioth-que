<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use App\Entity\Emprunt;
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
        $robert = new Utilisateur();
        $robert->setEmail('robert@biblio.fr')->setPrenom('Robert')->setNom('RESPONSABLE')
            ->setRoles(['ROLE_RESPONSABLE'])->setPassword($this->hasher->hashPassword($robert, 'password'))
            ->setDateAdhesion(new \DateTime('-1 year'))->setDateNaiss(new \DateTime('1975-01-01'))
            ->setAdressePostale('1 rue de la Gestion')->setNumTel('0102030405');
        $manager->persist($robert);

        $bruno = new Utilisateur();
        $bruno->setEmail('bruno@biblio.fr')->setPrenom('Bruno')->setNom('BIBLIOTHECAIRE')
            ->setRoles(['ROLE_BIBLIOTHECAIRE'])->setPassword($this->hasher->hashPassword($bruno, 'password'))
            ->setDateAdhesion(new \DateTime('-6 months'))->setDateNaiss(new \DateTime('1985-03-12'))
            ->setAdressePostale('5 ave des Livres')->setNumTel('0607080910');
        $manager->persist($bruno);

        $adherents = [];
        $nomsAdherents = ['Alice', 'Anthony', 'André', 'Agathe', 'Arthur', 'Adèle', 'Alain', 'Amélie', 'Adrien', 'Aude'];
        
        foreach ($nomsAdherents as $prenom) {
            $user = new Utilisateur();
            $user->setEmail(strtolower($prenom) . '@adherent.fr')->setPrenom($prenom)->setNom('ADHERENT')
                ->setRoles(['ROLE_ADHERENT'])->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setDateAdhesion(new \DateTime('-2 months'))->setDateNaiss(new \DateTime('2000-01-01'))
                ->setAdressePostale('Rue des Lecteurs')->setNumTel('0701020304');
            $manager->persist($user);
            $adherents[] = $user;
        }

        $categories = [];
        foreach (['Science-Fiction', 'Dystopie', 'Jeunesse', 'Essai', 'Informatique'] as $nomCat) {
            $c = new Categorie(); $c->setNom($nomCat);
            $manager->persist($c);
            $categories[$nomCat] = $c;
        }

        $auteurs = [];
        $dataAuteurs = [
            ['Frank', 'Herbert'], ['Isaac', 'Asimov'], ['George', 'Orwell'], 
            ['Antoine', 'de Saint-Exupéry'], ['Robert C.', 'Martin']
        ];
        foreach ($dataAuteurs as $a) {
            $aut = new Auteur(); $aut->setPrenom($a[0])->setNom($a[1])
                ->setDateNaissance(new \DateTimeImmutable('1950-01-01'));
            $manager->persist($aut);
            $auteurs[] = $aut;
        }

        $livres = [];
        for ($i = 1; $i <= 20; $i++) {
            $l = new Livre();
            $titre = match($i) {
                1 => 'Dune', 2 => 'Fondation', 3 => '1984', 4 => 'Le Petit Prince', 5 => 'Clean Code',
                default => "Livre Volume $i"
            };
            $l->setTitre($titre)->setLangue($i % 2 == 0 ? 'FR' : 'EN')->setDateSortie(new \DateTime('1950-01-01'))
              ->addAuteur($auteurs[array_rand($auteurs)])
              ->addCategory($categories[array_rand($categories)]);
            $manager->persist($l);
            $livres[] = $l;
        }

        $e1 = new Emprunt();
        $e1->setUtilisateur($adherents[1])->setLivre($livres[1]) // Anthony - Fondation
           ->setDateEmprunt(new \DateTime('2025-03-04'))->setDateRetour(new \DateTime('2025-03-22'));
        $manager->persist($e1);

        $e2 = new Emprunt();
        $e2->setUtilisateur($adherents[0])->setLivre($livres[0]) // Alice - Dune
           ->setDateEmprunt(new \DateTime('2025-03-05')); // Pas de date de retour = en cours
        $manager->persist($e2);

        for ($j = 0; $j < 13; $j++) {
            $emp = new Emprunt();
            $emp->setUtilisateur($adherents[array_rand($adherents)])
                ->setLivre($livres[array_rand($livres)])
                ->setDateEmprunt(new \DateTime('-' . ($j + 5) . ' days'));
 
            if ($j % 3 == 0) {
                $emp->setDateRetour(new \DateTime('-1 day'));
            }
            $manager->persist($emp);
        }

        $manager->flush();
    }
}