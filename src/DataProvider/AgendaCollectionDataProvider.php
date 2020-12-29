<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Agenda;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * Description of AgendaCollectionDataProvider
 *
 * @author symio
 */
final class AgendaCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {
    
    protected $security;
    protected $paginationExtension;
    protected $managerRegistry;
    
    public function __construct(Security $security, ManagerRegistry $managerRegistry, PaginationExtension $paginationExtension) {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
        $this->paginationExtension = $paginationExtension;
    }
    
    /**
     * @param array<string, mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return Agenda::class === $resourceClass;
    }
    
    /**
     * @param array<string, mixed> $context
     *
     * @throws \RuntimeException
     *
     * @return iterable<Agenda>
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable {
        /* @var $repo \App\Repository\AgendaRepository */
        $repo = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass);
        $queryBuilder = $repo->getUserAgendasByRightCodeQuery($this->security->getUser(), 'list');

        $this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface
            && $this->paginationExtension->supportsResult($resourceClass, $operationName, $context)) {
            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $context);
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
