<?php

namespace App\Repository;

use App\Entity\Reservations;
use App\Entity\Livre;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservations>
 */
class ReservationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservations::class);
    }

    public function searchByCategory(string $category): array
    {
        return $this->createQueryBuilder('r')
            ->join('r.livre', 'l')
            ->where('l.categorie = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function purgeExpired(\DateTimeInterface $cutoff): int
    {
        return $this->createQueryBuilder('r')
            ->delete()
            ->where('r.dateResa < :cutoff')
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->execute();
    }

    public function countActiveForUser(Utilisateur $user, \DateTimeInterface $cutoff): int
    {
        return (int) $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.utilisateur = :user')
            ->andWhere('r.dateResa >= :cutoff')
            ->setParameter('user', $user)
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findActiveByLivre(Livre $livre, \DateTimeInterface $cutoff): ?Reservations
    {
        return $this->createQueryBuilder('r')
            ->where('r.livre = :livre')
            ->andWhere('r.dateResa >= :cutoff')
            ->setParameter('livre', $livre)
            ->setParameter('cutoff', $cutoff)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Reservations[]
     */
    public function findActiveByUser(Utilisateur $user, \DateTimeInterface $cutoff): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.utilisateur = :user')
            ->andWhere('r.dateResa >= :cutoff')
            ->setParameter('user', $user)
            ->setParameter('cutoff', $cutoff)
            ->orderBy('r.dateResa', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Reservations[] Returns an array of Reservations objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reservations
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
