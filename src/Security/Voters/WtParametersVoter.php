<?php

namespace App\Security\Voters;

use App\Entity\User;
use App\Entity\WtParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;


/**
 * WtParameters Security Voter
 * Grant access to agenda parameters resources by owner
 *
 * @author symio
 */
class WtParametersVoter extends AgendaVoter {
    
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
        $this->entity = WtParameters::class;
    }
    
    protected function supports(string $attribute, $subject) {
        return parent::supports($attribute, $subject);
    }
    
    protected function voteOnResourceAttribute(string $attribute, $subject, TokenInterface $token) {
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            return false;
        }

        // you know $subject is an WtParameters entity object, thanks to `supports()`
        /** @var WtParameters $params */
        $params = $subject;
        $isGranted = false;
        
        if($user == $params->getUser()) {
            $isGranted = true;
        } else {
            if (is_null($params->getAgenda())) {
                $isGranted = false;
            } else {
                $isGranted = $this->voteOnSubResourceAttribute($attribute, $params->getAgenda(), $token);
            }
        }
        
        return ($isGranted);
    }
    
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        return $this->voteOnResourceAttribute($attribute, $subject, $token);
    }
    
}
