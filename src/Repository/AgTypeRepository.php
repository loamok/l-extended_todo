<?php

namespace App\Repository;

use App\Entity\AgType;
use App\Repository\BehavioursTraits\UuidIdentifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AgType|null find($id, $lockMode = null, $lockVersion = null)
 * @method AgType|null findOneBy(array $criteria, array $orderBy = null)
 * @method AgType[]    findAll()
 * @method AgType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgTypeRepository extends ServiceEntityRepository {
    
    use UuidIdentifiable;
    
    /**
     * 
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, AgType::class);
    }

}
