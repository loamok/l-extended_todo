<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use App\Repository\BehavioursTraits\UuidIdentifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository {
    
    use UuidIdentifiable;
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Event::class);
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
    
    public function getUserEventByRightCodeQuery(User $user, string $rightCode) {
        $qb = $this->createQueryBuilder('e');
        return
            $this->getUserSubWithRightCodeQuery('e', $qb, $this->security->getUser(), 'list');
    }
    
    public function getUserEventByRightCode(User $user, string $rightCode) {
        return $this->getUserEventByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
    }
    
    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value) {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
