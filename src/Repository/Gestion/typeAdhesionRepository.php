<?php

namespace App\Repository\Gestion;

use App\Entity\Admin\Association;
use App\Entity\Gestion\typeAdhesion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<typeAdhesion>
 */
class typeAdhesionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, typeAdhesion::class);
    }

    public function findActiveByAssociationAndDate(Association $association)
    {
        $today = new \DateTime(); // Date actuelle

        return $this->createQueryBuilder('t')
            ->andWhere('t.Asso = :association')
            ->andWhere('t.startAt <= :today')
            ->andWhere('t.endAt >= :today OR t.endAt IS NULL') // Si endAt est null, cela signifie pas de date de fin
            ->setParameter('association', $association)
            ->setParameter('today', $today)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return typeAdhesion[] Returns an array of typeAdhesion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?typeAdhesion
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
