<?php

namespace App\DataFixtures;

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
        $admin = new Utilisateur();
        $admin->setEmail('admin@biblio.fr')
            ->setNom('ADMIN')
            ->setPrenom('Principal')
            ->setRoles(['ROLE_ADMIN', 'ROLE_RESPONABLE']) // Vos nouveaux rôles
            ->setDateAdhesion(new \DateTime())
            ->setDateNaiss(new \DateTime('1990-01-01'))
            ->setAdressePostale('Service Administration')
            ->setNumTel('0000000000');

        $password = $this->hasher->hashPassword($admin, 'admin123');
        $admin->setPassword($password);

        $manager->persist($admin);
        $manager->flush();
    }
}