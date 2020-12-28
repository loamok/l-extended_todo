<?php

namespace App\Repository;

use App\Entity\Delegation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Delegation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delegation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delegation[]    findAll()
 * @method Delegation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delegation::class);
    }

    /**
     * 
     * @param App\Entity\Agenda $agenda
     * @return Delegation[] Returns an array of Delegation objects
     */
    public function findByAgenda($agenda) {
        return $this->createQueryBuilder('d')
            ->leftJoin('d.agenda', 'da')
            ->andWhere('da.id = :id')
            ->setParameter('id', $agenda->getId()->toBinary())
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Delegation
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
