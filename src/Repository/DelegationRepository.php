<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\Delegation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Delegation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Delegation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Delegation[]    findAll()
 * @method Delegation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DelegationRepository extends ServiceEntityRepository {
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Delegation::class);
    }

    /**
     * 
     * @param Agenda $agenda
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
    
    public function getUserDelegationByRightCodeQuery(User $user, string $rightCode) {
        $qb = $this->createQueryBuilder('d');
        return 
            $this->getUserSubWithRightCodeQuery('d', $qb, $user, 'list');
    }
    
    public function getUserDelegationByRightCode(User $user, string $rightCode) {
        return $this->getUserDelegationByRightCodeQuery($user, $rightCode)
            ->getQuery()
            ->getResult();    
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
