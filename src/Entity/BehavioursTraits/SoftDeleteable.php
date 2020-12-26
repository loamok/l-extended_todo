<?php
namespace App\Entity\BehavioursTraits;

use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 *
 * @author symio
 */
trait SoftDeleteable {
    
    use SoftDeleteableEntity {
        SoftDeleteableEntity::setDeletedAt as parentSetDeletedAt;
        SoftDeleteableEntity::getDeletedAt as parentGetDeletedAt;
    }
    
    /** 
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true) 
     */
    protected $timezone;
    
    /**
     * @var bool
     */
    private $localizedDeletedAt = false;

    /**
     * 
     * @param \DateTime|null $deletedAt
     * @return \self
     */
    public function setDeletedAt(?\DateTime $deletedAt = null): self {
        if(!is_null($deletedAt)) {
            $this->localizedDeletedAt = true;
            $this->timezone = $deletedAt->getTimeZone()->getName();
        }
        
        return $this->parentSetDeletedAt($deletedAt);
    }
    
    /**
     * 
     * @return \DateTime|null
     */
    public function getDeletedAt() : ?\DateTime {
        if (!$this->localizedDeletedAt && !is_null($this->deletedAt)) {
            $this->deletedAt->setTimeZone(new \DateTimeZone($this->timezone));
        }
        
        return $this->parentGetDeletedAt();
    }
    
    /**
     * 
     * @return string|null
     */
    public function getTimezone() : ?string {
        return $this->timezone;
    }
    
}
