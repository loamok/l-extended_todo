<?php

namespace App\Repository;

use App\Entity\Freebusy;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Freebusy|null find($id, $lockMode = null, $lockVersion = null)
 * @method Freebusy|null findOneBy(array $criteria, array $orderBy = null)
 * @method Freebusy[]    findAll()
 * @method Freebusy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FreebusyRepository extends ServiceEntityRepository {
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Freebusy::class);
    }

    public function getUserSubWithRightCodeQuery(string $alias, QueryBuilder $qb, User $user, string $rightCode) {
        return 
            $qb
                ->leftJoin($alias . '.agenda', 'a')
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
    
    public function getUserFreebusyByRightCodeQuery(User $user, string $rightCode) {
        $qb = $this->createQueryBuilder('f');
        return
            $this->getUserSubWithRightCodeQuery('f', $qb, $this->security->getUser(), 'list');
    }
    
    public function getUserFreebusyByRightCode(User $user, string $rightCode) {
        return $this->getUserFreebusyByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
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
