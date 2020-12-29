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
trait Startable {
    
    use UTCDatetimeAble;
    
    /**
     * @var \DateTimeInterface|null
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="datetime")
     * @Groups({"read", "write"})
     */
    protected $startAt;
    
    /**
     * 
     * @return DateTimeInterface|null
     */
    public function getStartAt(): ?DateTimeInterface {
        return $this->getDatetime('startAt');
    }

    /**
     * 
     * @param DateTimeInterface|null $startAt
     * @return \self
     */
    public function setStartAt(?DateTimeInterface $startAt): self {
        if(!is_null($startAt)) {
            $this->startAt = $startAt;
        }
        
        if(method_exists($this, 'respectRules')) {
        
            return $this->respectRules();
        } else {
            return $this;
        }
    }

}
