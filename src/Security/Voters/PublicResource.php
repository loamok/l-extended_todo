<?php

namespace App\Security\Voters;

use App\Entity\AgType;
use App\Entity\DelegationType;
use App\Entity\Rights;
use App\Entity\Timezone;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

/**
 * RoleGlobals Security Voter
 * Grant access to resources by role
 *
 * @author symio
 */
class PublicResource extends BaseVoter {
    
    protected $supports = [self::CREATE, self::LIST, self::READ, self::UPDATE, self::DELETE,];
    protected $writeSupports = [self::CREATE, self::UPDATE, self::DELETE,];
    protected $entity;
    protected $entities;
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
        $this->entities = [
            DelegationType::class, 
            Rights::class, 
            AgType::class, 
            Timezone::class, 
            User::class, 
        ];
    }
    
    protected function supports(string $attribute, $subject) {
        $subject = $this->getEntityObjectInPaginator($subject);
        
        if(!in_array($attribute, $this->supports)) {
            return false;
        }
        
        $res = false;
        foreach ($this->entities as $entityClassName) {
            if(is_a($subject, $entityClassName)) {
                $this->entity = $entityClassName;
                if(parent::supports($attribute, $subject) == true) {
                    $res = true;
                    break;
                }
            }
        }
        
        return $res;
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        $user = $this->security->getUser();
        
        if (!$user instanceof User) {
            return false;
        }

        $isGranted = true;
        if(!in_array($attribute, $this->writeSupports)) {
            $isGranted = true;
        } elseif(!in_array('admin', $user->getRoles())) {
            $isGranted = false;
        }
        
        return $isGranted;
        
        throw new LogicException('This code should not be reached!');
    }
    
}
