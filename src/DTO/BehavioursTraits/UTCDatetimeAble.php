<?php
namespace App\DTO\BehavioursTraits;

use App\Entity\Event;
use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

/**
 *
 * @author symio
 */
trait UTCDatetimeAble {
    
    /** 
     * @var string
     */
    public $timezone;
    
    /**
     * 
     * @param string $key
     * @param \App\Entity\* $entity
     * @return \self
     */
    protected function setDate(string $key, $entity) : self {
        $func = "get".ucfirst($key);
        $this->{$key} = $entity->{$func}();
        
        return $this->setDateAttr($key, $entity);
    }
    
    /**
     * 
     * @param string $key
     * @param \App\Entity\* $entity
     * @return \self
     */
    protected function setDateAttr(string $key, $entity) : self {
        if(is_null($this->timezone)) {
            $this->timezone = $entity->getTimezone();
        }
        if(!is_null($this->{$key}) && $this->{$key}->getTimezone()->getName() != $this->timezone) {
            $this->{$key} = $this->{$key}->setTimezone(new DateTimeZone($this->timezone));
        }
        
        return $this;
    }
    
}
