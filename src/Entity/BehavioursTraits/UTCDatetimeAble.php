<?php

namespace App\Entity\BehavioursTraits;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @author symio
 */
trait UTCDatetimeAble {
    
    /** 
     * @var string
     * 
     * @Gedmo\Versioned
     * @ORM\Column(type="string", nullable=true) 
     * @Groups({"read", "write"})
     */
    protected $timezone;
    
    /**
     *
     * @var boolean[]
     * @Groups({"none"})
     */
    protected $localized;

    /**
     * 
     * @param string $name
     * @param bool $val
     * @return self
     */
    protected function setLocalized(string $name, bool $val = false) : self { 
        $this->localized[$name] = $val;
        
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @return bool
     */
    protected function isLocalized(string $name) : bool {
        if(is_null($this->localized) || !array_key_exists($name, $this->localized)) {
            $this->setLocalized($name);
        }
        
        return $this->localized[$name];
    }
    
    protected function setDefaultTimeZone(DateTimeInterface $datetime) : DateTimeZone {
        $utc = new DateTimeZone('UTC');
        $nowUtc = new DateTime();
        $nowUtc->setTimezone($utc);
        $default = new DateTimeZone('Europe/Paris');
        if($datetime->getOffset() == $nowUtc->getOffset()) {
            return $default;
        }
        
        return $datetime->getTimezone();
    }
    
    /**
     * 
     * @param string $name
     * @param \DateTimeInterface|null $datetime
     * @return self
     */
    protected function setDatetime(string $name, ?\DateTimeInterface $datetime) : self {
        $this->setLocalized($name);
        if(!is_null($datetime) && !$this->isLocalized($name)) {
            $this->setLocalized($name, true);
            $this->timezone = $this->setDefaultTimeZone($datetime)->getName()                       ;
        }
        $this->{$name} = $datetime;
        
        return $this;
    }
    
    /**
     * 
     * @param string $name
     * @return \DateTimeInterface|null
     */
    protected function getDatetime(string $name) : ?\DateTimeInterface {
        if(!$this->isLocalized($name) && !is_null($this->{$name}) && $this->{$name} instanceof \DateTimeInterface) {
            $this->{$name}->setTimeZone(new \DateTimeZone($this->timezone));
            $this->setLocalized($name, true);
        }
        
        return $this->{$name};
    }
    
    /**
     * 
     * @param type $timezone
     * @return self
     */
    public function setTimezone($timezone) : self {
        $this->timezone = $timezone;
        
        return $this;
    }
    
    /**
     * 
     * @return string|null
     */
    public function getTimezone() : ?string {
        return $this->timezone;
    }
}
