<?php

namespace App\WorkTrackServices;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;

/**
 * Description of WTDaysRangeCalculator
 *
 * @author symio
 */
class WTDaysRangeCalculator {
    
    /**
     * Get all the dates for the selected view range (day, week, month)
     * 
     * @param array $params
     * @return array
     */
    public function getDates(array $params) : array {
        /* @var $dates array */
        $dates = [];
        /* @var $current DateTimeImmutable */
        $current = $params['start'];
        $nbDates = 0;
        do {
            if(!empty($dates)) {
                // @todo change 'P0Y0M1D' to some constant or parameters
                $current = $current->add(new DateInterval('P0Y0M1D'));
            }
            $dates[intval($current->format('W'))][] = $current;
            $nbDates++;
        } while (intval($current->format('Ymd')) < intval($params['end']->format('Ymd')) );
        
        return $dates;
    }
    
    /**
     * Set the selected day for display
     * 
     * @param array $params
     * @return array
     */
    public function selectedDateFromParams(array $params) : array {
        $params['selected'] = DateTimeImmutable::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        
        if($params['selected']->format('W') != $params['week'] && $params['mode'] == 'week') {
            $params['selected'] = $params['selected']->setISODate($params['year'], $params['week']);
        }
        
        return $params;
    }
    
    /**
     * Define params from parameters in the called route (paginator)
     * 
     * @param array $params
     * @param DateTimeInterface $now
     * @return array
     */
    public function paramsFromCalledParameters(array $params, DateTimeInterface $now) : array {
        if(!is_null($params['month'])) {
            $params['month'] = $params['month'];
        } else {
            $mFromYear       = DateTime::createFromFormat('dmY', $now->format('dm') . $params['year']);
            $params['month'] = intval($mFromYear->format('m'));
        }
        
        $params['month'] = ($params['month'] >= 10) ? $params['month'] : "0{$params['month']}";
        
        if(!is_null($params['day'])) {
            $params['day'] = $params['day'];
        } else {
            $dFromYearMonth = DateTime::createFromFormat('dmY', $now->format('d') . "{$params['month']}{$params['year']}");
            $params['day']  = intval($dFromYearMonth->format('d'));
        }
        
        $params['day']  = ($params['day'] >= 10) ? $params['day'] : "0{$params['day']}";
        $calculatedDate = DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        
        if($params['day'] > $calculatedDate->format('t')) {
            $params['day'] = $calculatedDate->format('t');
        }
        
        if(!is_null($params['week'])) {
            $params['week'] = $params['week'];
        } else {
            $wFromYearMonth = DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
            $params['week'] = intval($wFromYearMonth->format('W'));
        }        

        return $params;
    }
    
    /**
     * Set start and end boundaries
     * @todo change params['.. lines to some array_merging method
     * 
     * @param array $params
     * @return array
     */
    public function startAndEndForMonth(array $params) : array {
        $targeted = DateTimeImmutable::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        $fd = "01";
        $tw = $targeted->format('W'); $twI = intval($tw);
        $tm = $targeted->format('m'); 
        $tY = $targeted->format('Y'); $tYI = intval($tY);
        
        // start of work range
        $params['start'] = DateTimeImmutable::createFromFormat('dmY', "{$fd}{$tm}{$tY}");
        // first week
        $targetedWeekStart = $targeted->setISODate($tYI, $twI);
        
        if($targetedWeekStart->format('d') != $fd) {
            $targetedWeekStart = $targetedWeekStart->sub(new DateInterval('P0Y0M7D'));
        }
        
        $params['firstWeekStart'] = clone $params['start'];
        $params['firstWeekStart'] = $params['firstWeekStart']->setISODate($params['firstWeekStart']->format('Y'), $params['firstWeekStart']->format('W'));
        if($params['firstWeekStart']->format('m') == 12 && intval($params['start']->format('m')) == 1) {
            $params['firstWeekStart'] = $params['firstWeekStart']->setISODate($params['firstWeekStart']->format('Y') -1, $params['firstWeekStart']->format('W'));
        }
        $params['firstWeek'] = intval($params['firstWeekStart']->format('W'));
        
        // firstWeek first week of range start
        $params['end']         = DateTimeImmutable::createFromFormat('dmY', $params['start']->format('tmY'));
        $params['lastWeek']    = intval($params['end']->format('W'));
        $dLastWeek             = DateTimeImmutable::createFromFormat('dmY', $params['end']->format('dmY'));
        $dLastWeekStart        = $dLastWeek->setISODate($params['end']->format('Y'), $params['lastWeek']);
        $params['lastWeekEnd'] = $dLastWeekStart->add(new DateInterval('P0Y0M6D'));

        return $params;
    }
    
    /**
     * Set display parameters for table generations and paginator
     * @todo change params['.. lines to some array_merging method
     * 
     * @param array $params
     * @return array
     */
    public function defaultsDisplayParameters(Array $params) : Array {
        $params['mode'] = $params['mode'] ?? "month";
        
        $now = New DateTime();
        $params['year']     = ($params['year']  == 0 || is_null($params['year']))   ? intval($now->format('Y')) : $params['year'] ;
        $params['month']    = ($params['month'] == 0 || is_null($params['month']))  ? intval($now->format('m')) : $params['month'];
        $params['week']     = ($params['week']  == 0 || is_null($params['week']))   ? intval($now->format('W')) : $params['week'];
        $params['day']      = ($params['day']   == 0 || is_null($params['day']))    ? intval($now->format('d')) : $params['day'];
        $params['current']  = $now;
        $params['selected']  = $now;
        
        $params = $this->paramsFromCalledParameters($params, $now);
        
        $s = null; $e = null;
        
        switch ($params['mode']) {
            case 'month':
                $params = $this->startAndEndForMonth($params);
                $s = $params['firstWeekStart'];
                $e = $params['lastWeekEnd'];
                break;
            
            case 'week':
                $dts = new DateTimeImmutable();
                $params['start'] = $dts->setISODate($params['year'], $params['week']);
                // @todo change 'P0Y0M6D' and "2812" to some constant or parameters
                $params['end']      = $params['start']->add(new DateInterval('P0Y0M6D'));
                $lastYearDayMinus   = DateTime::createFromFormat('dmY', "2812" . $params['year'] -1);
                $lastYearDay        = DateTime::createFromFormat('dmY', "2812{$params['year']}");
                $params['maxWeeksMinus'] = $lastYearDayMinus->format('W');
                $params['maxWeeks'] = $lastYearDay->format('W');
                break;
            
            case 'day':
                // @todo change 'dmY' to some constant or parameters
                $params['start'] = DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
                $params['end'] = $params['start'];
                break;
        }
        
        $s = $s ?? $params['start'];
        $e = $e ?? $params['end'];
            
        $params['dates'] = $this->getDates(['start' => $s, 'end' => $e]);
        
        return $params;
    }
    
}
