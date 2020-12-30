<?php

namespace App\Repository;

use App\Entity\FbType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FbType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FbType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FbType[]    findAll()
 * @method FbType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FbTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FbType::class);
    }

    // /**
    //  * @return FbType[] Returns an array of FbType objects
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
    public function findOneBySomeField($value): ?FbType
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
