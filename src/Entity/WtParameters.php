<?php

namespace App\Entity;

use App\Repository\WtParametersRepository;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use Gedmo\Mapping\Annotation as Gedmo;

use App\Filters\UuidSearchFilter;
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
/**
 *     denormalizationContext={"groups"={"write"}},
 *          "groups"={"read"}
 * 
 */

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
 *     iri="WtParameters"
 * )
 * @ApiFilter(UuidSearchFilter::class, properties={"agenda": "exact"})
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=true, hardDelete=true)
 * @ORM\Entity(repositoryClass=WtParametersRepository::class)
 */
class WtParameters {
    
    use UuidIdentifiable,
        BlameableEntity, 
        SoftDeleteable,
        Timestampable,
        UTCDatetimeAble {
            UTCDatetimeAble::getTimezone insteadof SoftDeleteable, Timestampable;
        }

    /**
     * @var string Config name
     * 
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;
        
    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Agenda::class, inversedBy="wtParameters")
     * @ORM\JoinColumn(nullable=true)
     */
    private $agenda;

    /**
     * @ORM\Column(type="boolean")
     */
    private $defaultConfig;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $global;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $baseLunchBreakDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $extendedLunchBreakDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $shortedLunchBreakDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $baseWorkDayHoursDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $extendedWorkDayHoursDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $shortedWorkDayHoursDuration;
    
    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $baseTotalDayBreaksDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $extendedTotalDayBreaksDuration;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $shortedTotalDayBreaksDuration;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annualToilDaysNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $annualHolidayDaysNumber;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $noWorkBefore;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $noWorkAfter;

    public function __construct() {
        $this->global = false;
        $this->active = false;
        $this->defaultConfig = false;
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
    
    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }

    public function getAgenda(): ?Agenda {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self {
        $this->agenda = $agenda;

        return $this;
    }

    public function isDefaultConfig(): ?bool {
        return $this->getDefaultConfig();
    }
    
    public function getDefaultConfig(): ?bool {
        return $this->defaultConfig;
    }

    public function setDefaultConfig(bool $defaultConfig): self {
        $this->defaultConfig = $defaultConfig;

        return $this;
    }

    public function isActive(): ?bool {
        return $this->getActive();
    }
    
    public function getActive(): ?bool {
        return $this->active;
    }

    public function setActive(bool $active): self {
        $this->active = $active;

        return $this;
    }

    public function isGlobal(): ?bool {
        return $this->getGlobal();
    }
    
    public function getGlobal(): ?bool {
        return $this->global;
    }

    public function setGlobal(bool $global): self {
        $this->global = $global;

        return $this;
    }

    public function getBaseLunchBreakDuration(): ?\DateInterval {
        return $this->baseLunchBreakDuration;
    }

    public function setBaseLunchBreakDuration(?\DateInterval $baseLunchBreakDuration): self {
        $this->baseLunchBreakDuration = $baseLunchBreakDuration;

        return $this;
    }

    public function getExtendedLunchBreakDuration(): ?\DateInterval {
        return $this->extendedLunchBreakDuration;
    }

    public function setExtendedLunchBreakDuration(?\DateInterval $extendedLunchBreakDuration): self {
        $this->extendedLunchBreakDuration = $extendedLunchBreakDuration;

        return $this;
    }

    public function getShortedLunchBreakDuration(): ?\DateInterval {
        return $this->shortedLunchBreakDuration;
    }

    public function setShortedLunchBreakDuration(?\DateInterval $shortedLunchBreakDuration): self {
        $this->shortedLunchBreakDuration = $shortedLunchBreakDuration;

        return $this;
    }

    public function getBaseWorkDayHoursDuration(): ?\DateInterval {
        return $this->baseWorkDayHoursDuration;
    }

    public function setBaseWorkDayHoursDuration(?\DateInterval $baseWorkDayHoursDuration): self {
        $this->baseWorkDayHoursDuration = $baseWorkDayHoursDuration;

        return $this;
    }

    public function getExtendedWorkDayHoursDuration(): ?\DateInterval {
        return $this->extendedWorkDayHoursDuration;
    }

    public function setExtendedWorkDayHoursDuration(?\DateInterval $extendedWorkDayHoursDuration): self {
        $this->extendedWorkDayHoursDuration = $extendedWorkDayHoursDuration;

        return $this;
    }

    public function getShortedWorkDayHoursDuration(): ?\DateInterval {
        return $this->shortedWorkDayHoursDuration;
    }

    public function setShortedWorkDayHoursDuration(?\DateInterval $shortedWorkDayHoursDuration): self {
        $this->shortedWorkDayHoursDuration = $shortedWorkDayHoursDuration;

        return $this;
    }

    public function getBaseTotalDayBreaksDuration(): ?\DateInterval {
        return $this->baseTotalDayBreaksDuration;
    }

    public function setBaseTotalDayBreaksDuration(?\DateInterval $baseTotalDayBreaksDuration): self {
        $this->baseTotalDayBreaksDuration = $baseTotalDayBreaksDuration;

        return $this;
    }

    public function getExtendedTotalDayBreaksDuration(): ?\DateInterval {
        return $this->extendedTotalDayBreaksDuration;
    }

    public function setExtendedTotalDayBreaksDuration(?\DateInterval $extendedTotalDayBreaksDuration): self {
        $this->extendedTotalDayBreaksDuration = $extendedTotalDayBreaksDuration;

        return $this;
    }

    public function getShortedTotalDayBreaksDuration(): ?\DateInterval {
        return $this->shortedTotalDayBreaksDuration;
    }

    public function setShortedTotalDayBreaksDuration(?\DateInterval $shortedTotalDayBreaksDuration): self {
        $this->shortedTotalDayBreaksDuration = $shortedTotalDayBreaksDuration;

        return $this;
    }

    public function getAnnualToilDaysNumber(): ?int {
        return $this->annualToilDaysNumber;
    }

    public function setAnnualToilDaysNumber(?int $annualToilDaysNumber): self {
        $this->annualToilDaysNumber = $annualToilDaysNumber;

        return $this;
    }

    public function getAnnualHolidayDaysNumber(): ?int {
        return $this->annualHolidayDaysNumber;
    }

    public function setAnnualHolidayDaysNumber(?int $annualHolidayDaysNumber): self {
        $this->annualHolidayDaysNumber = $annualHolidayDaysNumber;

        return $this;
    }

    public function getNoWorkBefore(): ?\DateTimeInterface {
        return $this->noWorkBefore;
    }

    public function setNoWorkBefore(?\DateTimeInterface $noWorkBefore): self {
        $this->noWorkBefore = $noWorkBefore;

        return $this;
    }

    public function getNoWorkAfter(): ?\DateTimeInterface {
        return $this->noWorkAfter;
    }

    public function setNoWorkAfter(?\DateTimeInterface $noWorkAfter): self {
        $this->noWorkAfter = $noWorkAfter;

        return $this;
    }
}
