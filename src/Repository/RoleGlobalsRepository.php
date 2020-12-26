<?php

namespace App\Repository;

use App\Entity\RoleGlobals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RoleHasGlobalsRights|null find($id, $lockMode = null, $lockVersion = null)
 * @method RoleHasGlobalsRights|null findOneBy(array $criteria, array $orderBy = null)
 * @method RoleHasGlobalsRights[]    findAll()
 * @method RoleHasGlobalsRights[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleGlobalsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RoleGlobals::class);
    }

    // /**
    //  * @return RoleHasGlobalsRights[] Returns an array of RoleHasGlobalsRights objects
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
    public function findOneBySomeField($value): ?RoleHasGlobalsRights
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
