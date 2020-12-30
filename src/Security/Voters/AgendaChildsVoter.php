<?php

namespace App\Security\Voters;

use App\Entity\Agenda;
use App\Entity\Event;
use App\Entity\Freebusy;
use App\Entity\Journal;
use App\Entity\Todo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * AgendaChilds Security Voter
 * Grant access to Agenda Childs resources by Agenda delegations
 * Event
 * Todo
 * Journal
 * Freebusy
 *
 * @author symio
 */
class AgendaChildsVoter extends AgendaVoter {
    
    protected $supports = [
                self::READ, self::READ_FULL, self::UPDATE,
                self::DELETE, self::HISTORY_READ, self::HISTORY_RESTORE, 
                self::HISTORY_DELETE,
            ];
    protected $entity;
    protected $entities;
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;$this->entities = [
            Event::class, Todo::class, Journal::class, Freebusy::class, 
        ];
    }
    
    protected function supports(string $attribute, $subject) {
        foreach ($this->entities as $entityClassName) {
            if(is_a($subject, $entityClassName)) {
                $this->entity = $entityClassName;
                break;
            }
        }
        if(is_null($this->entity)) {
            return false;
        }
        
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        if(!method_exists($subject, 'getAgenda')) {
            return false;
        }
        
        $agenda = $this->em->getRepository(Agenda::class)->find($subject->getAgenda()->getId()->toBinary());
        
        return $this->voteOnSubResourceAttribute($attribute, $agenda, $token);
    }
    
}
