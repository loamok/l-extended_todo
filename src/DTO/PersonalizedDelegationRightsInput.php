<?php
namespace App\DTO;

use App\Entity\Delegation;
use App\Entity\DelegationType;
use App\Entity\PersonalizedDelegationRights;
use App\Entity\Rights;


/**
 * Description of DelegationInput
 *
 * @author symio
 */
class PersonalizedDelegationRightsInput {
    
    /**
     *
     * @var DelegationType
     */
    public $delegationType;
    
    /**
     *
     * @var Delegation
     */
    public $delegation;
    
    /**
     *
     * @var Rights
     */
    public $rights;
    
    /**
     * Transform of DTO to PersonalizedDelegationRights
     * 
     * @param PersonalizedDelegationRights $PersonalizedDelegationRights
     * @return \self
     */
    public function toPdr(PersonalizedDelegationRights &$pdr) : self {        

        $pdr->setDelegationType($this->delegationType)
               ->setDelegation($this->delegation)
               ->setRights($this->rights)
                ;
        
        return $this;
    }
    
}
