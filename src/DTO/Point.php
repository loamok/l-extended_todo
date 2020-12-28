<?php
namespace App\DTO;

/**
 * Description of PointDTO
 *
 * @author symio
 */
class Point {
    
    /**
     *
     * @var float
     */
    public $latitude;
    /**
     *
     * @var float
     */
    public $longitude;
    
    public function __construct($longitude = null, $latitude = null) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
    
    public function getLatitude() :float {
        return $this->latitude;
    }
    
    public function setLatitude(float $latitude) :self {
        $this->latitude = $latitude;
        
        return $this;
    }
    public function getLongitude() :float {
        return $this->longitude;
    }
    
    public function setLongitude(float $longitude) :self {
        $this->longitude = $longitude;
        
        return $this;
    }
    
    public function __toString() : string {
        return "{$this->latitude} {$this->longitude}";
    }
}
