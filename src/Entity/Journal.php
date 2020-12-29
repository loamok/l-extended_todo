<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
//use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Filters\UuidSearchFilter;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;
use App\Entity\BehavioursTraits\Startable;
use App\Entity\BehavioursTraits\Descriptable;
use App\Entity\BehavioursTraits\UuidIdentifiable;


use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use App\Repository\JournalRepository;
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
 *   iri="Journal"
 * )
 * @ApiFilter(UuidSearchFilter::class, properties={"agenda": "exact"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=JournalRepository::class)
 */
class Journal {
    
    use UuidIdentifiable,
        Descriptable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable, 
        Startable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable, Startable;
        }

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="journals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agenda;

    public function getAgenda(): ?Agenda {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self {
        $this->agenda = $agenda;

        return $this;
    }

}
