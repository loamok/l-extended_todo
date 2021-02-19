<?php

namespace App\WorkTrackServices;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Description of WtViewPrepare
 *
 * @author symio
 */
class WtViewPrepare {
    
    protected $security;
    protected $em;
    protected $as;
    protected $wttPs;

    public function __construct(Security $security, EntityManagerInterface $em, AgendaService $as, WtParametersService $wttPs) {
        $this->security = $security;
        $this->em = $em;
        $this->as = $as;
        $this->wttPs = $wttPs;
    }
    
    public function prepareMe(array $params) {
        return $params;
    }
}
