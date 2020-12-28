<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;

use App\DTO\AgendaInput;
use App\DTO\Agenda as AgendaOutput;

use App\Repository\AgendaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\UuidIdentifiable;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @todo configurer les extensions et compléter les champs et relations
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
 *          "groups"={"read"}
 *     },
 *     denormalizationContext={"groups"={"write"}},
 *     iri="Agenda"
 * )
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=AgendaRepository::class)
 */
class Agenda {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }

    /**
     * @var string Agenda name
     * 
     * @ORM\Column(type="string", length=255)
     * @Groups({"read", "write"})
     */
    private $name;
        
    /**
     * @var Timezone Associated Agenda Timezone
     * 
     * @ORM\ManyToOne(targetEntity=Timezone::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $tz;

    /**
     * @var AgType Agenda category (type)
     * 
     * @ORM\ManyToOne(targetEntity=AgType::class)
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read", "write"})
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Delegation::class, mappedBy="agenda", orphanRemoval=true)
     * @Groups({"read"})
     * @ApiSubresource
     */
    private $delegations;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="agenda")
     * @Groups({"read"})
     * @ApiSubresource
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity=Todo::class, mappedBy="agenda")
     * @Groups({"read"})
     * @ApiSubresource
     */
    private $todos;
    
    public function __construct() {
        $this->delegations = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->todos = new ArrayCollection();
    }
    
    /**
     * 
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     * @return self
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }
    
    /**
     * 
     * @return Timezone|null
     */
    public function getTz(): ?Timezone {
        return $this->tz;
    }

    /**
     * 
     * @param Timezone|null $tz
     * @return self
     */
    public function setTz(?Timezone $tz): self {
        $this->tz = $tz;

        return $this;
    }

    /**
     * 
     * @return AgType|null
     */
    public function getType(): ?AgType {
        return $this->type;
    }

    /**
     * 
     * @param AgType|null $type
     * @return self
     */
    public function setType(?AgType $type): self {
        $this->type = $type;

        return $this;
    }
    
    /**
     * @return Collection|Delegation[]
     */
    public function getDelegations(): Collection {
        return $this->delegations;
    }

    /**
     * 
     * @param Delegation $delegation
     * @return self
     */
    public function addDelegation(Delegation $delegation): self {
        if (!$this->delegations->contains($delegation)) {
            $this->delegations[] = $delegation;
            $delegation->setAgenda($this);
        }

        return $this;
    }

    /**
     * 
     * @param Delegation $delegation
     * @return self
     */
    public function removeDelegation(Delegation $delegation): self {
        if ($this->delegations->removeElement($delegation)) {
            // set the owning side to null (unless already changed)
            if ($delegation->getAgenda() === $this) {
                $delegation->setAgenda(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection {
        return $this->events;
    }

    public function addEvent(Event $event): self {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setAgenda($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getAgenda() === $this) {
                $event->setAgenda(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Todo[]
     */
    public function getTodos(): Collection {
        return $this->todos;
    }

    public function addTodo(Todo $todo): self {
        if (!$this->todos->contains($todo)) {
            $this->todos[] = $todo;
            $todo->setAgenda($this);
        }

        return $this;
    }

    public function removeTodo(Todo $todo): self {
        if ($this->todos->removeElement($todo)) {
            // set the owning side to null (unless already changed)
            if ($todo->getAgenda() === $this) {
                $todo->setAgenda(null);
            }
        }

        return $this;
    }
    
}
