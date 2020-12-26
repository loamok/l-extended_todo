<?php
namespace App\DTO;

use App\DTO\BehavioursTraits\Blameable;
use App\DTO\BehavioursTraits\SoftDeletable;
use App\DTO\BehavioursTraits\Timestampable;
use App\DTO\BehavioursTraits\UTCDatetimeAble;
use App\Entity\Delegation as DelegationEntity;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\Common\Collections\Collection;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 * Description of Delegation
 *
 * @author symio
 */
class Delegation {
    
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
     * @var \App\Entity\Agenda
     */
    public $agenda;
    
    /**
     *
     * @var \App\Entity\User
     */
    public $owner;
    
    /**
     *
     * @var \App\Entity\User
     */
    public $user;
    
    /**
     *
     * @var array|PersonalizedDelegationRights[]
     */
    public $personalizedDelegationRights;
    
    /**
     *
     * @var \App\Entity\DelegationType
     */
    public $delegationType;

    /**
     * Transform of Delegation to DTO
     * 
     * @param DelegationEntity $delegation
     * @return \self
     */
    public function fromDelegation(DelegationEntity $delegation) : self {
        
        $this->id = $delegation->getId();
        
        // @todo move properties to DTO traits
        $dates = ['createdAt', 'updatedAt', 'deletedAt'];
        foreach ($dates as $d) {
            $this->setDate($d, $delegation);
        }
        $this->fromBlameableEntity($delegation);
        $this->deleted = $delegation->isDeleted();
        $this->agenda = $delegation->getAgenda();
        $this->user = $delegation->getUser();
        $this->owner = $delegation->getOwner();
        $this->personalizedDelegationRights = $delegation->getPersonalizedDelegationRights();
        $this->delegationType = $delegation->getDelegationType();
        
        return $this;
    }
}
