<?php

namespace App\Entity;

/**
 * Description of ForType
 *
 * @author symio
 */
abstract class ForType extends LetEnum {
    
    const TODO = "todo";
    const EVENT = "event";
    const AGENDA = "agenda";
    const AGTYPE = "agtype";
    const FREEBUSY = "freebusy";
    
}
