<?php

namespace App\DTO;

use App\Entity\Event;
use CrEOF\Spatial\PHP\Types\Geography\Point as GeoPoint;

/**
 * Description of GeoTypeInput
 *
 * @author symio
 */
class GeoType {
    
    /**
     *
     * @var Point
     */
    public $geo;
    
    public function fromEntity(&$entity) : self {
        if(!is_null($entity->getGeo())) {
            $this->geo = new Point($entity->getGeo()->getLongitude(), $entity->getGeo()->getLatitude());
        } else {
            $this->geo = null;
        }
        
        return $this;
    }
    
    public function toEntity(&$entity) : self {
        if(!is_null($this->geo)) {
            $entity->setGeo(new GeoPoint([$this->geo->getLongitude(), $this->geo->getLatitude()]));
        }
        
        return $this;
    }
}
