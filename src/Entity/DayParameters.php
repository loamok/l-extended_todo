<?php

namespace App\Entity;

use App\Repository\DayParametersRepository;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;

use Gedmo\Mapping\Annotation as Gedmo;

use App\Filters\UuidSearchFilter;
use App\Entity\BehavioursTraits\BlameableEntity;
use App\Entity\BehavioursTraits\UuidIdentifiable;
use App\Entity\BehavioursTraits\SoftDeleteable;
use App\Entity\BehavioursTraits\Timestampable;
use App\Entity\BehavioursTraits\UTCDatetimeAble;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\IdGenerator\UuidV4Generator;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

use Symfony\Component\Serializer\Annotation\Groups;

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
 *     normalizationContext={
 *          "jsonld_embed_context"=true,
 *     },
 *     iri="DayParameters"
 * )
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=DayParametersRepository::class)
 */
class DayParameters {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amPauseStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amPauseEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $amEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmPauseStart;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmPauseEnd;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $pmEnd;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $amPauseDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $pmPauseDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $amPmPauseDuration;

    /**
     * @ORM\OneToOne(targetEntity=WtParameters::class, inversedBy="dayParameters", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $wtParameter;

    public function getAmStart(): ?\DateTimeInterface {
        return $this->amStart;
    }

    public function setAmStart(?\DateTimeInterface $amStart): self {
        $this->amStart = $amStart;

        return $this;
    }

    public function getAmPauseStart(): ?\DateTimeInterface {
        return $this->amPauseStart;
    }

    public function setAmPauseStart(?\DateTimeInterface $amPauseStart): self {
        $this->amPauseStart = $amPauseStart;

        return $this;
    }

    public function getAmPauseEnd(): ?\DateTimeInterface {
        return $this->amPauseEnd;
    }

    public function setAmPauseEnd(?\DateTimeInterface $amPauseEnd): self {
        $this->amPauseEnd = $amPauseEnd;

        return $this;
    }

    public function getAmEnd(): ?\DateTimeInterface {
        return $this->amEnd;
    }

    public function setAmEnd(?\DateTimeInterface $amEnd): self {
        $this->amEnd = $amEnd;

        return $this;
    }

    public function getPmStart(): ?\DateTimeInterface {
        return $this->pmStart;
    }

    public function setPmStart(?\DateTimeInterface $pmStart): self {
        $this->pmStart = $pmStart;

        return $this;
    }

    public function getPmPauseStart(): ?\DateTimeInterface {
        return $this->pmPauseStart;
    }

    public function setPmPauseStart(?\DateTimeInterface $pmPauseStart): self {
        $this->pmPauseStart = $pmPauseStart;

        return $this;
    }

    public function getPmPauseEnd(): ?\DateTimeInterface {
        return $this->pmPauseEnd;
    }

    public function setPmPauseEnd(?\DateTimeInterface $pmPauseEnd): self {
        $this->pmPauseEnd = $pmPauseEnd;

        return $this;
    }

    public function getPmEnd(): ?\DateTimeInterface {
        return $this->pmEnd;
    }

    public function setPmEnd(?\DateTimeInterface $pmEnd): self {
        $this->pmEnd = $pmEnd;

        return $this;
    }

    public function getAmPauseDuration(): ?\DateInterval {
        return $this->amPauseDuration;
    }

    public function setAmPauseDuration(?\DateInterval $amPauseDuration): self {
        $this->amPauseDuration = $amPauseDuration;

        return $this;
    }

    public function getPmPauseDuration(): ?\DateInterval {
        return $this->pmPauseDuration;
    }

    public function setPmPauseDuration(?\DateInterval $pmPauseDuration): self {
        $this->pmPauseDuration = $pmPauseDuration;

        return $this;
    }

    public function getAmPmPauseDuration(): ?\DateInterval {
        return $this->amPmPauseDuration;
    }

    public function setAmPmPauseDuration(?\DateInterval $amPmPauseDuration): self {
        $this->amPmPauseDuration = $amPmPauseDuration;

        return $this;
    }

    public function getWtParameter(): ?WtParameters
    {
        return $this->wtParameter;
    }

    public function setWtParameter(WtParameters $wtParameter): self
    {
        $this->wtParameter = $wtParameter;

        return $this;
    }
    
}
