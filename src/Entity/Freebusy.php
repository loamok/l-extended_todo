<?php

namespace App\Entity;

use App\Repository\FreebusyRepository;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Filters\UuidSearchFilter;
use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;
use App\Entity\BehavioursTraits\Durationable;
use App\Entity\BehavioursTraits\Startable;
use App\Entity\BehavioursTraits\Descriptable;
use App\Entity\BehavioursTraits\Relatable;
use App\Entity\BehavioursTraits\UuidIdentifiable;


use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

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
 *   iri="Freebusy"
 * )
 * @ApiFilter(UuidSearchFilter::class, properties={"agenda": "exact"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=FreebusyRepository::class)
 */
class Freebusy {
    
    /**
     * @ORM\OneToMany(targetEntity=Related::class, mappedBy="freebusy")
     * @ApiSubresource
     */
    private $relateds;
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable, 
        Durationable,
        Relatable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable, Durationable;
        }

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="freebusies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agenda;

    /**
     * @ORM\ManyToOne(targetEntity=FbType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function __construct() {
        
        $this->initRelatable();
    }

    public function getAgenda(): ?Agenda {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self {
        $this->agenda = $agenda;

        return $this;
    }

    public function getType(): ?FbType {
        return $this->type;
    }

    public function setType(?FbType $type): self {
        $this->type = $type;

        return $this;
    }
    
}
