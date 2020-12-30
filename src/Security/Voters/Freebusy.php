<?php

namespace App\Security\Voters;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Freebusy Security Voter
 * Grant access to Freebusy resources by Agenda delegations
 *
 * @author symio
 */
class Freebusy extends AgendaVoter {
    
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
        $this->entity = \App\Entity\Freebusy::class;
    }
    
    protected function supports(string $attribute, $subject) {
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        // you know $subject is an Journal entity object, thanks to `supports()`
        /** @var \App\Entity\Freebusy $freebusy */
        $freebusy = $subject;
        $agenda = $this->em->getRepository(\App\Entity\Agenda::class)->find($freebusy->getAgenda()->getId()->toBinary());
        
        return $this->voteOnSubResourceAttribute($attribute, $agenda, $token);
    }
    
}
