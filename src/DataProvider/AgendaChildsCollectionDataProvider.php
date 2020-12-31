<?php

namespace App\DataProvider;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\PaginationExtension;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryResultCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Event;
use App\Entity\Freebusy;
use App\Entity\Journal;
use App\Entity\Related;
use App\Entity\Todo;
use App\Repository\JournalRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;
use Symfony\Component\Security\Core\Security;

/**
 * Description of AgendaCollectionDataProvider
 *
 * @author symio
 */
final class AgendaChildsCollectionDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface {
    
    protected $security;
    protected $paginationExtension;
    protected $managerRegistry;
    protected $entities;


    public function __construct(Security $security, ManagerRegistry $managerRegistry, PaginationExtension $paginationExtension) {
        $this->security = $security;
        $this->managerRegistry = $managerRegistry;
        $this->paginationExtension = $paginationExtension;
        $this->entities = [
            Event::class => "Event", Todo::class => "Todo", Journal::class => "Journal",
            Freebusy::class => "Freebusy", Related::class => "Related",
        ];
    }
    
    /**
     * @param array<string, mixed> $context
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        $res = false;
        if(array_key_exists($resourceClass, $this->entities)) {
            $res = true;
        }
        return $res;
    }
    
    /**
     * @param array<string, mixed> $context
     *
     * @throws RuntimeException
     *
     * @return iterable
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable {
        $repo = $this->managerRegistry
            ->getManagerForClass($resourceClass)
            ->getRepository($resourceClass);
        $func = "getUser{$this->entities[$resourceClass]}ByRightCodeQuery";
        $queryBuilder = $repo->{$func}($this->security->getUser(), 'list');

        $this->paginationExtension->applyToCollection($queryBuilder, new QueryNameGenerator(), $resourceClass, $operationName, $context);

        if ($this->paginationExtension instanceof QueryResultCollectionExtensionInterface
            && $this->paginationExtension->supportsResult($resourceClass, $operationName, $context)) {
            return $this->paginationExtension->getResult($queryBuilder, $resourceClass, $operationName, $context);
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
