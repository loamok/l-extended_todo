<?php

namespace App\Entity;

use App\Repository\RelatedRepository;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

/**
 * 
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_USER')", 
 *          "pagination_items_per_page"=20
 *     },
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('list', object)" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('create', object)" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('read', object)" },
 *          "put" = { "security" = "is_granted('update', object)" },
 *          "delete" = { "security" = "is_granted('delete', object)" }
 *     },
 *     normalizationContext={
 *          "jsonld_embed_context"=true,
 *     },
 *     iri="Related"
 * )
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=RelatedRepository::class)
 * 
 */
class Related {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable, 
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }

    /**
     * @ORM\ManyToOne(targetEntity=RelType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agenda;
    
    /**
     * @ORM\ManyToOne(targetEntity=Journal::class, inversedBy="relateds")
     */
    private $journal;

    /**
     * @ORM\ManyToOne(targetEntity=Todo::class, inversedBy="relateds")
     */
    private $todo;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="relateds")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity=Freebusy::class, inversedBy="relateds")
     */
    private $freebusy;

    public function setParent($entity = null, ?string $entityType = null) : self {
        if(is_null($entityType) && !is_null($entity)) {
            if(is_a($entity, Event::class)) {
                $entityType = ucfirst('event');
            } elseif(is_a($entity, Todo::class)) {
                $entityType = ucfirst('todo');
            } elseif(is_a($entity, Journal::class)) {
                $entityType = ucfirst('journal');
            } elseif(is_a($entity, Freebusy::class)) {
                $entityType = ucfirst('freebusy');
            }
        }
        
        if(!is_null($entityType)) {
            $func = 'set'.ucfirst($entityType);
            $this->{$func}($entity);
        }
        
        return $this;
    }
    
    public function getParent(string $entityType) {
        $func = 'get'.ucfirst($entityType);
        return $this->{$func}();
    }

    public function getType(): ?RelType {
        return $this->type;
    }

    public function setType(?RelType $type): self {
        $this->type = $type;

        return $this;
    }

    public function getAgenda(): ?Agenda {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self {
        $this->agenda = $agenda;

        return $this;
    }

    public function getJournal(): ?Journal {
        return $this->journal;
    }

    public function setJournal(?Journal $journal): self {
        $this->journal = $journal;

        return $this;
    }

    public function getTodo(): ?Todo {
        return $this->todo;
    }

    public function setTodo(?Todo $todo): self {
        $this->todo = $todo;

        return $this;
    }

    public function getEvent(): ?Event {
        return $this->event;
    }

    public function setEvent(?Event $event): self {
        $this->event = $event;

        return $this;
    }

    public function getFreebusy(): ?Freebusy {
        return $this->freebusy;
    }

    public function setFreebusy(?Freebusy $freebusy): self {
        $this->freebusy = $freebusy;

        return $this;
    }
    
}
