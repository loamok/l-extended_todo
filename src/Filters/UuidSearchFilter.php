<?php

namespace App\Filters;

/**
 * Description of UuidSearchFilter
 *
 * @author symio
 */
class UuidSearchFilter extends \ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter {
    
    protected function filterProperty(string $property, $value, \Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null) {
        if(preg_match('~[^\x20-\x7E\t\r\n]~', $value) <= 0) {
            try {
                $uid = new \Symfony\Component\Uid\UuidV4($value);
                $v = $uid->toBinary();
            } catch (\InvalidArgumentException $e) {
                $v = $value;
            }
            $value = $v;
        }
        
        parent::filterProperty($property, $value, $queryBuilder, $queryNameGenerator, $resourceClass, $operationName);
    }
    
    protected function addWhereByStrategy(string $strategy, \Doctrine\ORM\QueryBuilder $queryBuilder, \ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface $queryNameGenerator, string $alias, string $field, $value, bool $caseSensitive) {
        if(preg_match('~[^\x20-\x7E\t\r\n]~', $value) <= 0) {
            try {
                $uid = new \Symfony\Component\Uid\UuidV4($value);
                $v = $uid->toBinary();
            } catch (\InvalidArgumentException $e) {
                $v = $value;
            }
            $value = $v;
        }
        
        parent::addWhereByStrategy($strategy, $queryBuilder, $queryNameGenerator, $alias, $field, $value, $caseSensitive);
    }
}
