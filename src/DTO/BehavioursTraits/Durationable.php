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
trait Durationable {
    
    /**
     *
     * @var DateTimeInterface
     */
    public $startAt;
    
    /**
     *
     * @var DateTimeInterface
     */
    public $endAt;
    
    /**
     *
     * @var DateInterval
     */
    public $duration;
    
}
