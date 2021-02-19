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
    protected $wDrCalc;

    public function __construct(Security $security, EntityManagerInterface $em, AgendaService $as, WtParametersService $wttPs, WTDaysRangeCalculator $wDrCalc) {
        $this->security = $security;
        $this->em = $em;
        $this->as = $as;
        $this->wttPs = $wttPs;
        $this->wDrCalc = $wDrCalc;
    }
    
    public function prepareMe(array $params) {
        if(array_key_exists('paginateParams', $params)) {
            $params['paginateParams']['year']  = (array_key_exists('year',  $params['paginateParams'])) ? intval($params['paginateParams']['year'])  : 0;
            $params['paginateParams']['month'] = (array_key_exists('month', $params['paginateParams'])) ? intval($params['paginateParams']['month']) : 0;
            $params['paginateParams']['week']  = (array_key_exists('week',  $params['paginateParams'])) ? intval($params['paginateParams']['week'])  : 0;
            $params['paginateParams']['day']   = (array_key_exists('day',   $params['paginateParams'])) ? intval($params['paginateParams']['day'])   : 0;
        }
        $calculatedRange = $this->wDrCalc->defaultsDisplayParameters($params['paginateParams']);
        $calculatedRange = $this->wDrCalc->selectedDateFromParams($calculatedRange);
        
        $params['calculatedRange'] = $calculatedRange;
        
        return $params;
    }
}
