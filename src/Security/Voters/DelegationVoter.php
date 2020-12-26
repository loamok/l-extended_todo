<?php

namespace App\Security\Voters;

use App\Entity\Delegation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;

/**
 * Delegation Security Voter
 * Grant access to Delegation resources
 *
 * @author symio
 */
class DelegationVoter extends BaseVoter {
    
    protected $supports = [self::READ, self::UPDATE, self::DELETE,];
    protected $entity;
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
        $this->entity = Delegation::class;
    }
    
    protected function supports(string $attribute, $subject) {
//        $this->entity = Delegation::class;
        
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is an Agenda entity object, thanks to `supports()`
        /** @var Delegation $delegation */
        $delegation = $subject;
        
        $isGranted = false;
        
        if($delegation->getUser()->getId() == $user->getId()) {
            $isGranted = true;
        }
        if($delegation->getOwner()->getId() == $user->getId())  {
            $isGranted = true;
        }
        
        return ($isGranted);
        
        throw new LogicException('This code should not be reached!');
    }
    
}
