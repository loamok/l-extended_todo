<?php
namespace App\DTO;

use App\Entity\Agenda;
use App\Entity\AgType;
use App\Entity\Timezone;

/**
 * Description of AgendaInput
 *
 * @author symio
 */
class AgendaInput {
    
    /**
     * 
     * @var string
     */
    public $name;
    
    /**
     *
     * @var AgType
     */
    public $type;
    /**
     *
     * @var Timezone
     */
    public $tz;
    
    /**
     * Transform of DTO to Agenda
     * 
     * @param Agenda $agenda
     * @return \self
     */
    public function toAgenda(Agenda &$agenda) : self {        

        $agenda
               ->setName($this->name)
               ->setType($this->type)
               ->setTz($this->tz)
               ->setTimezone($this->tz->getName())
                ;
        
        return $this;
    }
    
}
