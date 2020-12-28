<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

use CrEOF\Spatial\PHP\Types\Geography\GeographyInterface;
use App\DBAL\Types\Point;

use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;
use App\Entity\BehavioursTraits\Durationable;
use App\Entity\BehavioursTraits\Geolocable;
use App\Entity\BehavioursTraits\Descriptable;
use App\Entity\BehavioursTraits\UuidIdentifiable;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use App\Repository\EventRepository;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use Doctrine\ORM\Mapping as ORM;

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
 *     iri="Event"
 * )
 * @ApiFilter(SearchFilter::class, properties={"agenda": "exact"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=EventRepository::class)
 * 
 */
class Event {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable, 
        Durationable,
        Descriptable,
        Geolocable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable, Durationable;
        }

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
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
