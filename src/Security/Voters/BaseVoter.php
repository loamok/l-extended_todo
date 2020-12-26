<?php

namespace App\Security\Voters;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


/**
 * Description of BaseVoter
 *
 * @author symio
 */
class BaseVoter extends Voter {
    
    protected $supports;
    protected $entity;
    const CREATE = 'create';
    const LIST = 'list';
    const READ = 'read';
    const READ_FULL = 'read_full';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const HISTORY_LIST = 'history_list';
    const HISTORY_READ = 'history_read';
    const HISTORY_RESTORE = 'history_restore';
    const HISTORY_DELETE = 'history_delete';
    
    protected function getEntityObjectInPaginator($subject) {
        $res = $subject;
        /** @var \ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator $subject */
        if(is_a($subject, \ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator::class)) {
            if($subject->count() > 0) {
                $res = $subject->getIterator()[0];
            } else {
                $res = null;
            }
        }
        return $res;
    }
    
    protected function supports(string $attribute, $subject) {
        if(is_null($this->entity)) {
            return false;
        }
        if(is_null($this->supports)) {
            $this->supports = [
                self::CREATE, self::LIST, self::READ, self::READ_FULL, self::UPDATE,
                self::DELETE, self::HISTORY_READ, self::HISTORY_LIST, self::HISTORY_RESTORE, 
                self::HISTORY_DELETE,
            ];
        }
        if(!in_array($attribute, $this->supports)) {
            return false;
        }
        
        // only vote on `entity` objects
        if (!is_a($subject, $this->entity)) {
            return false;
        }

        return true;
        
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        throw new LogicException('This code should not be reached!');
    }
    
}
