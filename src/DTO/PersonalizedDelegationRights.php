<?php
namespace App\DTO;

use App\DTO\BehavioursTraits\Blameable;
use App\DTO\BehavioursTraits\SoftDeletable;
use App\DTO\BehavioursTraits\Timestampable;
use App\DTO\BehavioursTraits\UTCDatetimeAble;
use App\Entity\PersonalizedDelegationRights as PersonalizedDelegationRightsEntity;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 * Description of PersonalizedDelegationRights
 *
 * @author symio
 */
class PersonalizedDelegationRights {
    
    use SoftDeletable, 
        UTCDatetimeAble, 
        Timestampable,
        Blameable;
    
    /**
     *
     * @var UuidV4
     */
    public $id;
    
    /**
     *
     * @var \App\Entity\DelegationType
     */
    public $delegationType;
    
    /**
     *
     * @var \App\Entity\Delegation
     */
    public $delegation;
    
    /**
     *
     * @var \App\Entity\Rights
     */
    public $rights;
    
    /**
     * Transform of PersonalizedDelegationRights to DTO
     * 
     * @param PersonalizedDelegationRightsEntity $pdr
     * @return \self
     */
    public function fromPdr(PersonalizedDelegationRightsEntity $pdr) : self {
        
        $this->id = $pdr->getId();
        
        // @todo move properties to DTO traits
        $dates = ['createdAt', 'updatedAt', 'deletedAt'];
        foreach ($dates as $d) {
            $this->setDate($d, $pdr);
        }
        $this->fromBlameableEntity($pdr);
        $this->deleted = $pdr->isDeleted();
        $this->delegationType = $pdr->getDelegationType();
        $this->delegation = $pdr->getDelegation();
        $this->rights = $pdr->getRights();
        
        return $this;
    }
}
