<?php

namespace App\Repository;

use App\Entity\Freebusy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Freebusy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Freebusy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Freebusy[]    findAll()
 * @method Freebusy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreebusyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Freebusy::class);
    }

    // /**
    //  * @return Freebusy[] Returns an array of Freebusy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Freebusy
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
