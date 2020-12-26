<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;

use App\DTO\DelegationInput;
use App\DTO\Delegation as DelegationOutput;

use App\Repository\PersonalizedDelegationRightsRepository;
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
 *   normalizationContext={ "jsonld_embed_context"=true },
 *   input={"class"=PersonalizedDelegationRightsInput::class,"name"="PersonalizedDelegationRights", "iri"="PersonalizedDelegationRights"},
 *   output={"class"=PersonalizedDelegationRightsOutput::class,"name"="PersonalizedDelegationRights", "iri"="PersonalizedDelegationRights"},
 *   iri="PersonalizedDelegationRights"
 * )
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=PersonalizedDelegationRightsRepository::class)
 */
class PersonalizedDelegationRights {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }
    
    /**
     * @ORM\ManyToOne(targetEntity=DelegationType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegationType;

    /**
     * @ORM\ManyToOne(targetEntity=Delegation::class, inversedBy="personalizedDelegationRights")
     * @ORM\JoinColumn(nullable=false)
     */
    private $delegation;

    /**
     * @ORM\ManyToOne(targetEntity=Rights::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $rights;

    public function getDelegationType(): ?DelegationType
    {
        return $this->delegationType;
    }

    public function setDelegationType(?DelegationType $delegationType): self
    {
        $this->delegationType = $delegationType;

        return $this;
    }

    public function getRights(): ?Rights
    {
        return $this->rights;
    }

    public function setRights(?Rights $rights): self
    {
        $this->rights = $rights;

        return $this;
    }
}
