<?php

namespace App\Security\Voters;

use App\Entity\Agenda;
use App\Entity\Delegation;
use App\Entity\Rights;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;


/**
 * Agenda Security Voter
 * Grant access to agenda resources by delegations
 *
 * @author symio
 */
class AgendaVoter extends BaseVoter {
    
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
        $this->entity = Agenda::class;
    }
    
    protected function supports(string $attribute, $subject) {
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnSubResourceAttribute(string $attribute, $subject, TokenInterface $token) {
        $user = $this->security->getUser();
        if (!$user instanceof \App\Entity\User) {
            return false;
        }

        // you know $subject is an Agenda entity object, thanks to `supports()`
        /** @var Agenda $agenda */
        $agenda = $subject;
        
        $delegations = $this->em->getRepository(Delegation::class)->findByAgenda($agenda);
        $delegationFound = false;
        $isGranted = false;
        /** @var Delegation $delegation */
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
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        return $this->voteOnSubResourceAttribute($attribute, $subject, $token);
    }
    
}
