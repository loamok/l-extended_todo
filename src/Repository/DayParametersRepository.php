<?php

namespace App\Repository;

use App\Entity\DayParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DayParameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method DayParameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method DayParameters[]    findAll()
 * @method DayParameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DayParametersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DayParameters::class);
    }

    // /**
    //  * @return DayParameters[] Returns an array of DayParameters objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DayParameters
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
