<?php
namespace App\Entity\BehavioursTraits;

use Gedmo\Timestampable\Traits\TimestampableEntity;
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
trait Timestampable {
    
    use TimestampableEntity {
        TimestampableEntity::setCreatedAt as parentSetCreatedAt;
        TimestampableEntity::getCreatedAt as parentGetCreatedAt;
        TimestampableEntity::setUpdatedAt as parentSetUpdatedAt;
        TimestampableEntity::getUpdatedAt as parentGetUpdatedAt;
    }
    
    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    protected $updatedAt;
    
    /** 
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true) 
     * @Groups({"read"})
     */
    protected $timezone;
    
    /**
     * @var bool
     * @Groups({"none"})
     */
    private $localizedCreatedAt = false;
    
    /**
     * @var bool
     * @Groups({"none"})
     */
    private $localizedUpdatedAt = false;

    /**
     * 
     * @param \DateTimeInterface $createdAt
     * @return \self
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self {
        if(!is_null($createdAt)) {
            $this->localizedCreatedAt = true;
            $this->timezone = $createdAt->getTimeZone()->getName();
        }
        
        return $this->parentSetCreatedAt($createdAt);
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getCreatedAt() : ?\DateTime {
        if (!$this->localizedCreatedAt && !is_null($this->createdAt)) {
            if(is_null($this->timezone)) {
                $this->timezone = $this->createdAt->getTimeZone()->getName();
            }
            $this->createdAt->setTimeZone(new \DateTimeZone($this->timezone));
        }
        
        return $this->parentGetCreatedAt();
    }
    
    /**
     * 
     * @param \DateTime $updatedAt
     * @return \self
     */
    public function setUpdatedAt(\DateTime $updatedAt): self {
        if(!is_null($updatedAt)) {
            $this->localizedUpdatedAt = true;
            $this->timezone = $updatedAt->getTimeZone()->getName();
        }
        
        return $this->parentSetUpdatedAt($updatedAt);
    }
    
    /**
     * 
     * @return \DateTime
     */
    public function getUpdatedAt() : ?\DateTime {
        if (!$this->localizedCreatedAt && !is_null($this->updatedAt)) {
            $this->updatedAt->setTimeZone(new \DateTimeZone($this->timezone));
        }
        
        return $this->parentGetUpdatedAt();
    }
    
    /**
     * 
     * @return string|null
     */
    public function getTimezone() : ?string {
        return $this->timezone;
    }
}
