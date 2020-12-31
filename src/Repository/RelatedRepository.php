<?php

namespace App\Repository;

use App\Entity\Related;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Related|null find($id, $lockMode = null, $lockVersion = null)
 * @method Related|null findOneBy(array $criteria, array $orderBy = null)
 * @method Related[]    findAll()
 * @method Related[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Related::class);
    }

    public function getUserRelatedByRightCodeQuery(User $user, string $rightCode) {
        $qb = $this->createQueryBuilder('r');
        return 
            $qb
                ->leftJoin('r.agenda', 'a')
                ->leftJoin('a.delegations', 'ad')
                ->leftJoin('ad.delegationType', 'adt')
                ->leftJoin('adt.rights', 'adtr')
                ->leftJoin('ad.user', 'adu')
                ->leftJoin('ad.owner', 'ado')
            ->andWhere('adtr.code = :rightCode')
            ->andWhere($qb->expr()->orX(
                    $qb->expr()->eq('adu.id', ':user'),
                    $qb->expr()->eq('ado.id', ':user')))
                ->setParameter('user', $user->getId()->toBinary())
                ->setParameter('rightCode', $rightCode)
                    ;
    }
    
    public function getUserRelatedByRightCode(User $user, string $rightCode) {
        return $this->getUserRelatedByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
    }
    
    // /**
    //  * @return Related[] Returns an array of Related objects
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
    public function findOneBySomeField($value): ?Related
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
