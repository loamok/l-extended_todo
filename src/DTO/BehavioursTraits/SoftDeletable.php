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
trait SoftDeletable {
    
    /**
     *
     * @var DateTimeInterface|null
     */
    public $deletedAt;
    
    /**
     *
     * @var boolean
     */
    public $deleted;
    
}
