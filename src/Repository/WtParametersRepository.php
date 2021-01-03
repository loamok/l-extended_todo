<?php

namespace App\Repository;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\WtParameters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WtParameters|null find($id, $lockMode = null, $lockVersion = null)
 * @method WtParameters|null findOneBy(array $criteria, array $orderBy = null)
 * @method WtParameters[]    findAll()
 * @method WtParameters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WtParametersRepository extends ServiceEntityRepository {
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, WtParameters::class);
    }

    public function getParamsForUserAndAgendaQuery(User $user, ?Agenda $agenda = null) {
        $qb = $this->createQueryBuilder('wtp');
        $qb ->select()
            ->join('wtp.user', 'u')
            ->where($qb->expr()->eq('u.id', ':uid'))
            ->setParameter('uid', $user->getId()->toBinary())
        ;
        if(!is_null($agenda) && !is_null($agenda->getId())) {
            $qb->join('wtp.agenda', 'a')
                ->andWhere($qb->expr()->orX(
                    $qb->expr()->isNull('wtp.agenda'),
                    $qb->expr()->eq('a.id', ':aid')
                ))
                ->setParameter('aid', $agenda->getId()->toBinary())
            ;
        }
        
        return $qb;
        
    }
    public function getParamsForUserAndAgenda(User $user, ?Agenda $agenda = null) {
        
        return $this->getParamsForUserAndAgendaQuery($user, $agenda)->getQuery()->getResult();
    }
    
    public function findGlobalParamForUser(User $user) {
        $qb = $this->getParamsForUserAndAgendaQuery($user);
        
        $qb 
            ->andWhere($qb->expr()->eq('wtp.global', ':g'))
            ->andWhere($qb->expr()->eq('wtp.defaultConfig', ':g'))
            ->setParameter('g', true)
        ;
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    // /**
    //  * @return WtParameters[] Returns an array of WtParameters objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WtParameters
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
