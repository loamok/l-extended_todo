<?php
namespace App\DTO;

use App\DTO\BehavioursTraits\Blameable;
use App\DTO\BehavioursTraits\SoftDeletable;
use App\DTO\BehavioursTraits\Timestampable;
use App\DTO\BehavioursTraits\UTCDatetimeAble;
use App\Entity\Agenda as AgendaEntity;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

/**
 * Description of Agenda
 *
 * @author symio
 */
class Agenda {
    
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
     * @var string
     */
    public $name;
    
    /**
     *
     * @var \App\Entity\Timezone
     */
    public $tz;
    
    /**
     *
     * @var \App\Entity\AgType
     */
    public $type;

    /**
     * Transform of Agenda to DTO
     * 
     * @param AgendaEntity $agenda
     * @return \self
     */
    public function fromAgenda(AgendaEntity $agenda) : self {
        
        $this->id = $agenda->getId();
        
        // @todo move properties to DTO traits
        $dates = ['createdAt', 'updatedAt', 'deletedAt'];
        foreach ($dates as $d) {
            $this->setDate($d, $agenda);
        }
        $this->fromBlameableEntity($agenda);
        $this->name = $agenda->getName();
        $this->deleted = $agenda->isDeleted();
        $this->tz = $agenda->getTz();
        $this->type = $agenda->getType();
        
        return $this;
    }
}
