<?php
namespace App\DTO\BehavioursTraits;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;

/**
 *
 * @author symio
 */
trait Timestampable {
    
    /**
     * @var DateTimeInterface
     */
    public $createdAt;
    
    /**
     * @var DateTimeInterface
     */
    public $updatedAt;
    
}
