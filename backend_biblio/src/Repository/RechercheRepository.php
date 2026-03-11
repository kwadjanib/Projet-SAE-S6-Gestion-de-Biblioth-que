<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;

class RechercheRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function searchLivres(
        ?string $titre,
        ?string $auteur,
        ?string $categorie,
        ?string $langue,
        ?\DateTimeInterface $dateMin,
        ?\DateTimeInterface $dateMax
    ): array {
        $qb = $this->entityManager->createQueryBuilder()
            ->select('l', 'a', 'c')
            ->from(Livre::class, 'l')
            ->leftJoin('l.auteurs', 'a')
            ->leftJoin('l.categories', 'c')
            ->distinct();

        if ($titre) {
            $qb->andWhere('LOWER(l.titre) LIKE :titre')
                ->setParameter('titre', '%' . strtolower($titre) . '%');
        }

        if ($auteur) {
            $qb->andWhere('LOWER(a.nom) LIKE :auteur OR LOWER(a.prenom) LIKE :auteur')
                ->setParameter('auteur', '%' . strtolower($auteur) . '%');
        }

        if ($categorie) {
            $qb->andWhere('LOWER(c.nom) LIKE :categorie')
                ->setParameter('categorie', '%' . strtolower($categorie) . '%');
        }

        if ($langue) {
            $qb->andWhere('LOWER(l.langue) LIKE :langue')
                ->setParameter('langue', '%' . strtolower($langue) . '%');
        }

        if ($dateMin) {
            $qb->andWhere('l.dateSortie >= :dateMin')
                ->setParameter('dateMin', $dateMin);
        }

        if ($dateMax) {
            $qb->andWhere('l.dateSortie <= :dateMax')
                ->setParameter('dateMax', $dateMax);
        }

        return $qb->getQuery()->getResult();
    }
}
