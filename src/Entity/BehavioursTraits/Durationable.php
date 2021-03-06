<?php
namespace App\Entity\BehavioursTraits;

use Doctrine\ORM\Mapping as ORM;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @author symio
 */
trait Durationable {
    
    use UTCDatetimeAble, Startable;

    /**
     * @var \DateTimeInterface|null
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime")
     * @Groups({"read", "write"})
     */
    protected $endAt;

    /**
     * @var DateInterval|null
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="dateinterval")
     * @Groups({"read", "write"})
     */
    protected $duration;

    /**
     * 
     * @return DateTimeInterface|null
     */
    public function getEndAt(): ?DateTimeInterface {
        return $this->getDatetime('endAt');
    }

    /**
     * 
     * @param DateTimeInterface|null $endAt
     * @return \self
     */
    public function setEndAt(?DateTimeInterface $endAt): self {
        if(!is_null($endAt)) {
            $this->endAt = $endAt;
        }
        
        return $this->respectRules();
    }

    /**
     * 
     * @return DateInterval|null
     */
    public function getDuration(): ?DateInterval {
        return $this->duration;
    }

    /**
     * 
     * @param DateInterval|null $duration
     * @return \self
     */
    public function setDuration(?DateInterval $duration): self {
        if(!is_null($duration)) {
            $this->duration = $duration;
        }
        
        return $this->respectRules();
    }
    /**
     * 
     * @return \self
     */
    protected function respectRules() : self {        
        if(!is_null($this->startAt) && !is_null($this->endAt)) {
            $this->duration = $this->startAt->diff($this->endAt, true);
        } 
        if (!is_null($this->duration) && is_null($this->startAt) && !is_null($this->endAt)) {
            $startAt = clone $this->endAt;
            $startAt = $startAt->sub($this->duration);
            $startAt = $startAt->setTimezone($this->endAt->getTimezone());
            $this->setDatetime('startAt', $startAt);
        } 
        if (!is_null($this->duration) && !is_null($this->startAt) && is_null($this->endAt)) {
            $endAt = clone $this->startAt;
            $endAt = $endAt->add($this->duration);
            $endAt = $endAt->setTimezone($this->startAt->getTimezone());
            $this->setDatetime('endAt', $endAt);
        }
        if(!$this->isLocalized('startAt')) {
            $this->setDatetime('startAt', $this->startAt);
        }
        if(!$this->isLocalized('endAt')) {
            $this->setDatetime('endAt', $this->endAt);
        }
        
        return $this;
    }
}
