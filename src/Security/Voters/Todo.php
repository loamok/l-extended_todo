<?php

namespace App\Security\Voters;

use App\Entity\Rights;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Todo Security Voter
 * Grant access to Todo resources by Agenda delegations
 *
 * @author symio
 */
class Todo extends AgendaVoter {
    
    protected $supports = [
                self::READ, self::READ_FULL, self::UPDATE,
                self::DELETE, self::HISTORY_READ, self::HISTORY_RESTORE, 
                self::HISTORY_DELETE,
            ];
    protected $entity;
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
        $this->entity = \App\Entity\Todo::class;
    }
    
    protected function supports(string $attribute, $subject) {
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        // you know $subject is an Todo entity object, thanks to `supports()`
        /** @var \App\Entity\Todo $todo */
        $todo = $subject;
        $agenda = $this->em->getRepository(\App\Entity\Agenda::class)->find($todo->getAgenda()->getId()->toBinary());
        
        return $this->voteOnSubResourceAttribute($attribute, $agenda, $token);
    }
    
}
