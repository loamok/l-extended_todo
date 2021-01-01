<?php

namespace App\Repository;

use App\Entity\RelType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RelType|null find($id, $lockMode = null, $lockVersion = null)
 * @method RelType|null findOneBy(array $criteria, array $orderBy = null)
 * @method RelType[]    findAll()
 * @method RelType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RelType::class);
    }

    // /**
    //  * @return RelType[] Returns an array of RelType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RelType
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
