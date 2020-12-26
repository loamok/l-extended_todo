<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\User;
use App\Repository\BehavioursTraits\UuidIdentifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Agenda|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agenda|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agenda[]    findAll()
 * @method Agenda[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgendaRepository extends ServiceEntityRepository {
    
    use UuidIdentifiable;
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Agenda::class);
    }

    public function getUserAgendasByRightCodeQuery(User $user, string $rightCode) {
        return 
            $this->createQueryBuilder('a')
                ->leftJoin('a.delegations', 'ad')
                ->leftJoin('ad.delegationType', 'adt')
                ->leftJoin('adt.rights', 'adtr')
                ->leftJoin('ad.user', 'adu')
            ->andWhere('adu.id = :user')
            ->andWhere('adtr.code = :rightCode')
                ->setParameter('user', strtolower($user->getId()))
                ->setParameter('rightCode', $rightCode);
    }
    
    public function getUserAgendasByRightCode(User $user, string $rightCode) {
        return $this->getUserAgendasByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
    }
    
    // /**
    //  * @return Agenda[] Returns an array of Agenda objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Agenda
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
