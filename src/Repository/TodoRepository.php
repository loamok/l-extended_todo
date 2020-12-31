<?php

namespace App\Repository;

use App\Entity\Todo;
use App\Entity\User;
use App\Repository\BehavioursTraits\UuidIdentifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Todo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todo[]    findAll()
 * @method Todo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodoRepository extends ServiceEntityRepository {
    
    use UuidIdentifiable;
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Todo::class);
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
    
    public function getUserTodoByRightCodeQuery(User $user, string $rightCode) {
        $qb = $this->createQueryBuilder('t');
        return 
            $this->getUserSubWithRightCodeQuery('t', $qb, $this->security->getUser(), 'list');
    }
    
    public function getUserTodoByRightCode(User $user, string $rightCode) {
        return $this->getUserTodoByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
    }
    
    // /**
    //  * @return Todo[] Returns an array of Todo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Todo
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
