<?php

namespace App\Repository;

use App\Entity\DelegationType;
use App\Repository\BehavioursTraits\UuidIdentifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DelegationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DelegationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DelegationType[]    findAll()
 * @method DelegationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegationTypeRepository extends ServiceEntityRepository {
    
    use UuidIdentifiable;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DelegationType::class);
    }

    // /**
    //  * @return DelegationType[] Returns an array of DelegationType objects
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
    public function findOneBySomeField($value): ?DelegationType
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
