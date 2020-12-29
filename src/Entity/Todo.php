<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
//use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Filters\UuidSearchFilter;

use CrEOF\Spatial\PHP\Types\Geography\GeographyInterface;
use App\DBAL\Types\Point;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;
use App\Entity\BehavioursTraits\Durationable;
use App\Entity\BehavioursTraits\Startable;
use App\Entity\BehavioursTraits\Geolocable;
use App\Entity\BehavioursTraits\Descriptable;
use App\Entity\BehavioursTraits\Locationable;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use App\Repository\TodoRepository;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('list', object)" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('create', object)" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('read', object)" },
 *          "put" = { "security" = "is_granted('update', object)" },
 *          "delete" = { "security" = "is_granted('delete', object)" }
 *     },
 *   normalizationContext={ 
 *      "jsonld_embed_context"=true 
 *   },
 *   iri="Todo"
 * )
 * @ApiFilter(UuidSearchFilter::class, properties={"agenda": "exact"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=TodoRepository::class)
 */
class Todo {
    
    use UuidIdentifiable,
        Descriptable,
        Locationable,
        Geolocable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable, 
        Durationable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable, Durationable;
        }

    /**
     * @var bool 
     * 
     * @ORM\Column(type="boolean")
     */
    private $completed;

    /**
     * @var integer 
     * 
     * @ORM\Column(type="integer")
     */
    private $percent;

    /**
     * @var integer
     * 
     * @ORM\Column(type="smallint")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="todos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agenda;

    public function __construct() {
        $this->completed = false;
        $this->percent = 0;
        $this->priority = 0;
    }
    
    /**
     * 
     * @return bool|null
     */
    public function getCompleted(): ?bool {
        return $this->completed;
    }

    /**
     * 
     * @param bool $completed
     * @return \self
     */
    public function setCompleted(?bool $completed): self {
        if(is_null($completed)) {
            $completed = false;
        }
        $this->completed = $completed;
        if($this->completed === true) {
            $this->percent = 100;
        }
        
        return $this;
    }
    
    /**
     * Check if the entity has been soft deleted.
     *
     * @return bool
     */
    public function isCompleted(): bool {
        $res = false;
        if(!is_null($this->completed) && $this->completed != false) {
            $res = ($this->completed === true);
        }
        
        return $res;
    }

    /**
     * 
     * @return int|null
     */
    public function getPercent(): ?int {
        return $this->percent;
    }

    /**
     * 
     * @param int $percent
     * @return \self
     */
    public function setPercent(?int $percent): self {
        if(is_null($percent)) {
            $percent = 0;
        }
        
        $this->percent = $percent;
        if($this->percent == 100) {
            $this->completed = true;
        }
        
        return $this;
    }

    /**
     * 
     * @return int|null
     */
    public function getPriority(): ?int {
        return $this->priority;
    }

    /**
     * 
     * @param int $priority
     * @return \self
     */
    public function setPriority(?int $priority): self {
        if(is_null($priority)) {
            $priority = 0;
        }
        
        $this->priority = $priority;

        return $this;
    }

    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }
    
}
