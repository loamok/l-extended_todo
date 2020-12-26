<?php

namespace App\Security\Voters;

use App\Entity\Agenda;
use App\Entity\Rights;
use App\Entity\RoleGlobals;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;


/**
 * RoleGlobals Security Voter
 * Grant access to resources by role
 *
 * @author symio
 */
class RoleGlobalsVoter extends BaseVoter {
    
    protected $supports = [self::CREATE, self::LIST,];
    protected $entity;
    protected $entities;
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
        $this->entities = [Agenda::class, \App\Entity\Delegation::class];
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
        if(is_null($subject)) {
            $res = true;
        } else {
        }
        
        return $res;
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        $user = $this->security->getUser();
        
        if (!$user instanceof User) {
            return false;
        }

        $isGranted = false;
        foreach ($user->getRoles() as $role) {
            dump($role);
            /** @var RoleGlobals $rg */
            $rg = $this->em->getRepository(RoleGlobals::class)->findOneBy(['role' => $role]);
            if(is_null($rg)) {
                continue;
            }
            /** @var Rights $right */
            foreach ($rg->getRights() as $right) {
                if($right->getCode() == $attribute) {
                    $isGranted = true;
                    break;
                }
            }
            if($isGranted) {
                break;
            }
        }
        
        return $isGranted;
        
        throw new LogicException('This code should not be reached!');
    }
    
}
