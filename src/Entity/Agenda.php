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
/*
 *     collectionOperations={
 *          "get" = { "security_post_denormalize" = "is_granted('list', object)" }, 
 *          "post"= { "security_post_denormalize" = "is_granted('create', object)" }
 *     },
 *     itemOperations={
 *          "get" = { "security" = "is_granted('read', object)" },
 *          "put" = { "security" = "is_granted('update', object)" },
 *          "delete" = { "security" = "is_granted('delete', object)" }
 *     },
 * 
 */
/**
 * @todo configurer les extensions et compléter les champs et relations
 * @todo implémenter les délégations et la sécurité par voters
 * 
 * @ApiResource(
 *     attributes={
 *          "security"="is_granted('ROLE_USER')", 
 *          "pagination_items_per_page"=20
 *     },
 *     normalizationContext={ "jsonld_embed_context"=true },
 *     input={"class"=AgendaInput::class,"name"="Agenda", "iri"="Agenda"},
 *     output={"class"=AgendaOutput::class,"name"="Agenda", "iri"="Agenda"},
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
     */
    private $name;
        
    /**
     * @var Timezone Associated Agenda Timezone
     * 
     * @ORM\ManyToOne(targetEntity=Timezone::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $tz;

    /**
     * @var AgType Agenda category (type)
     * 
     * @ORM\ManyToOne(targetEntity=AgType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * 
     * @return string
     */
    public function __toString() : string {
        return $this->getId()->toString();
    }
    
    public function __construct() {
//        $this->delegations = new ArrayCollection();
//        $this->events = new ArrayCollection();
//        $this->todos = new ArrayCollection();
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
    
}
