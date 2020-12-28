<?php

namespace App\Security\Voters;

use App\Entity\Rights;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Event Security Voter
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
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is an Event entity object, thanks to `supports()`
        /** @var \App\Entity\Event $event */
        $event = $subject;
        $agenda = $this->em->getRepository(\App\Entity\Agenda::class)->find($event->getAgenda()->getId()->toBinary());
        
        $delegations = $this->em->getRepository(\App\Entity\Delegation::class)->findByAgenda($agenda);
        dump($delegations);
        $delegationFound = false;
        $isGranted = false;
        /** @var \App\Entity\Delegation $delegation */
        foreach ($delegations as $delegation) {
            if($delegation->getUser() == $user) {
                $delegationFound = true;
                /** @var Rights $right */
                foreach ($delegation->getRights() as $right) {
                    if($right->getCode() == $attribute) {
                        $isGranted = true;
                        break;
                    }
                }
                break;
            }
        }
        
        return ($delegationFound && $isGranted);
        
        throw new LogicException('This code should not be reached!');
    }
    
}
