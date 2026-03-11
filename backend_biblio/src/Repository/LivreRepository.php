<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }
    // src/Repository/LivreRepository.php

    public function findByTitle(string $query): array
    {
    return $this->createQueryBuilder('l')
        ->where('l.titre LIKE :query')
        ->setParameter('query', '%' . $query . '%')
        ->getQuery()
        ->getResult();
    }

    public function findByCriteria(
        ?string $titre,
        ?string $auteur,
        ?string $categorie,
        ?string $langue,
        ?\DateTimeInterface $dateMin,
        ?\DateTimeInterface $dateMax
    ): array {
        $qb = $this->createQueryBuilder('l')
            ->leftJoin('l.auteurs', 'a')
            ->leftJoin('l.categories', 'c')
            ->addSelect('a', 'c')
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
    //    /**
    //     * @return Livre[] Returns an array of Livre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Livre
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
