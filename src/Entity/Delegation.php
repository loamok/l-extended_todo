<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Repository\DelegationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Blameable\Traits\BlameableEntity;

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

/**
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
 *   normalizationContext={
 *          "jsonld_embed_context"=true,
 *   },
 *   iri="Delegation"
 * )
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=DelegationRepository::class)
 */
class Delegation {

    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }
    
    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="delegations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agenda;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="delegations")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="delegationsOwned")
     */
    private $owner;

    /**
     * @var DelegationType
     * @ORM\ManyToOne(targetEntity=DelegationType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegationType;
    
    /**
     * @ORM\OneToMany(targetEntity=PersonalizedDelegationRights::class, mappedBy="delegation", orphanRemoval=true)
     */
    private $personalizedDelegationRights;

    public function __construct() {
        $this->personalizedDelegationRights = new ArrayCollection();
    }

    public function getAgenda(): ?Agenda {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self {
        $this->agenda = $agenda;

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }

    public function getOwner(): ?User {
        return $this->owner;
    }

    public function setOwner(?User $owner): self {
        $this->owner = $owner;

        return $this;
    }

    public function getDelegationType(): ?DelegationType {
        return $this->delegationType;
    }

    public function setDelegationType(?DelegationType $delegationType): self {
        $this->delegationType = $delegationType;

        return $this;
    }

    /**
     * @return Collection|PersonilizedDelegationsRights[]
     */
    public function getRights(): Collection {
        return $this->delegationType->getRights();
    }

    /**
     * @return Collection|PersonalizedDelegationRights[]
     */
    public function getPersonalizedDelegationRights(): Collection {
        return $this->personalizedDelegationRights;
    }

    public function addPersonalizedDelegationRight(PersonalizedDelegationRights $personalizedDelegationRight): self {
        if (!$this->personalizedDelegationRights->contains($personalizedDelegationRight)) {
            $this->personalizedDelegationRights[] = $personalizedDelegationRight;
            $personalizedDelegationRight->setDelegation($this);
        }

        return $this;
    }

    public function removePersonalizedDelegationRight(PersonalizedDelegationRights $personalizedDelegationRight): self {
        if ($this->personalizedDelegationRights->removeElement($personalizedDelegationRight)) {
            // set the owning side to null (unless already changed)
            if ($personalizedDelegationRight->getDelegation() === $this) {
                $personalizedDelegationRight->setDelegation(null);
            }
        }

        return $this;
    }
}
