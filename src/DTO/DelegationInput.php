<?php
namespace App\DTO;

use App\Entity\Agenda;
use App\Entity\Delegation;
use App\Entity\DelegationType;
use App\Entity\PersonalizedDelegationRights;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

/**
 * Description of DelegationInput
 *
 * @author symio
 */
class DelegationInput {
    
    /**
     *
     * @var Agenda
     */
    public $agenda;
    
    /**
     *
     * @var User
     */
    public $owner;
    
    /**
     *
     * @var User
     */
    public $user;
    
    /**
     *
     * @var array|PersonalizedDelegationRights[]
     */
    public $personalizedDelegationRights;
    
    /**
     *
     * @var DelegationType
     */
    public $delegationType;
    
    /**
     * Transform of DTO to Delegation
     * 
     * @param Delegation $delegation
     * @return \self
     */
    public function toDelegation(Delegation &$delegation) : self {        

        $delegation->setAgenda($this->agenda)
               ->setOwner($this->owner)
               ->setUser($this->user)
               ->setDelegationType($this->delegationType)
                ;
        /* @var $pdr PersonalizedDelegationRights */
        if(!empty($this->personalizedDelegationRights)){
            foreach ($this->personalizedDelegationRights as $pdr) {
                $delegation->addPersonalizedDelegationRight($pdr);
            }
        }
        
        return $this;
    }
    
}
