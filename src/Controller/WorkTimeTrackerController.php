<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\WtParameters;
use App\Form\AgendaFormType;
use App\Form\WtParametersType;
use App\Repository\AgendaRepository;
use App\WorkTrackServices\AgendaService;
use App\WorkTrackServices\WtParametersService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class WorkTimeTrackerController extends AbstractController {
    
    /**
     * 
     * @var AgendaService
     */
    protected $agendaSvc;
    /**
     * 
     * @var WtParametersService
     */
    protected $wtParamsSvc;

    /**
     * Controller constructor
     * 
     * @param AgendaService $agendaSvc
     * @param WtParametersService $wtParamsSvc
     */
    public function __construct(AgendaService $agendaSvc, WtParametersService $wtParamsSvc) {
        $this->agendaSvc = $agendaSvc;
        $this->wtParamsSvc = $wtParamsSvc;
    }

    /**
     * Get variables common to all actions
     * 
     * @param User|null $user
     * @param Agenda|null $agenda
     * @param WtParameters|null $params
     * @return array
     */
    protected function getCommonVariables(?User $user = null, ?Agenda $agenda = null, ?WtParameters $params = null) {
        $user = $user ?? $this->getUser();
        $agenda = $agenda ?? new Agenda();
        $this->denyAccessUnlessGranted('list', $agenda);
        $params = $params ?? new WtParameters();
        $this->denyAccessUnlessGranted('list', $params);
        
        $emptyParams = new WtParameters();
        $epForm = $this->createForm(WtParametersType::class, $emptyParams);
        
        return [
            'agendas'       => $this->agendaSvc->getWTAgendasForUser($user),
            'params'        => $this->wtParamsSvc->getParamsForUserAndAgenda($user, $agenda),
            'globalParam'   => $this->wtParamsSvc->findGlobalParamsForUser($user),
            'epForm'        => $epForm,
            'epFormView'    => $epForm->createView(),
        ];
    }
    
    /**
     * Index Action list worktracking agendas for user
     * 
     * @Route("/worktime_tracker", name="worktime_tracker")
     * @IsGranted("ROLE_USER")
     * 
     * @param User|null $u
     * @return Response
     */
    public function index(?User $u = null): Response {
        /* @var $user UserInterface */
        $user = $u ?? $this->getUser();
        
        return $this->render('work_time_tracker/index.html.twig', array_merge([
            'controller_name' => 'WorkTimeTrackerController',
        ], $this->getCommonVariables($user)));
    }
    
    /**
     * Create Action creates a worktracking agenda for user
     * 
     * @Route("/worktime_tracker/create", name="worktime_tracker_create")
     * @IsGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param User|null $u
     * @return Response
     */
    public function create(Request $request, ?User $u = null): Response {
        /* @var $user UserInterface */
        $user = $u ?? $this->getUser();
        $agenda = new Agenda();
        $this->denyAccessUnlessGranted('create', $agenda);
        
        $form = $this->createForm(AgendaFormType::class, $agenda);
        
        $res = null;
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();
            
            $res =  $this->redirectToRoute('worktime_tracker_display', ['id' => $agenda->getId()]);
        }
        
        if(is_null($res)) {
            $res =  $this->render('work_time_tracker/create.html.twig',  array_merge([
                    'agenda_form' => $form->createView(),
                    'agenda' => $agenda
                ], 
                $this->getCommonVariables($user, $agenda)
            ));
        }
        
        return $res;
    }
    
    /**
     * Get all the dates for the selected view range (day, week, month)
     * 
     * @param array $params
     * @return array
     */
    protected function getDates(array $params) {
        /* @var $dates array */
        $dates = [];
        /* @var $current \DateTimeImmutable */
        $current = $params['start'];
        
        do {
            if(!empty($dates)) {
                // @todo change 'P0Y0M1D' to some constant or parameters
                $current = $current->add(new \DateInterval('P0Y0M1D'));
            }
            $dates[intval($current->format('W'))][] = $current;
        } while ($current != $params['end']);
        
        return $dates;
    }
    
    /**
     * Set the selected day for display
     * 
     * @param array $params
     * @return array
     */
    protected function selectedDateFromParams(array $params) {
        $params['selected'] = \DateTimeImmutable::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        
        if($params['selected']->format('W') != $params['week'] && $params['mode'] == 'week') {
            $params['selected'] = $params['selected']->setISODate($params['year'], $params['week']);
        }
        
        return $params;
    }
    
    /**
     * Define params from parameters in the called route (paginator)
     * 
     * @param array $params
     * @param \DateTime $now
     * @return array
     */
    protected function paramsFromCalledParameters(array $params, \DateTime $now) {
        if(!is_null($params['month'])) {
            $params['month'] = $params['month'];
        } else {
            $mFromYear       = \DateTime::createFromFormat('dmY', $now->format('dm') . $params['year']);
            $params['month'] = intval($mFromYear->format('m'));
        }
        
        $params['month'] = ($params['month'] >= 10) ? $params['month'] : "0{$params['month']}";
        
        if(!is_null($params['day'])) {
            $params['day'] = $params['day'];
        } else {
            $dFromYearMonth = \DateTime::createFromFormat('dmY', $now->format('d') . "{$params['month']}{$params['year']}");
            $params['day']  = intval($dFromYearMonth->format('d'));
        }
        
        $params['day']  = ($params['day'] >= 10) ? $params['day'] : "0{$params['day']}";
        $calculatedDate = \DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        
        if($params['day'] > $calculatedDate->format('t')) {
            $params['day'] = $calculatedDate->format('t');
        }
        
        if(!is_null($params['week'])) {
            $params['week'] = $params['week'];
        } else {
            $wFromYearMonth = \DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
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
    protected function startAndEndForMonth(array $params) {
        $targeted = \DateTimeImmutable::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        $fd = "01";
        $tw = $targeted->format('W'); $twI = intval($tw);
        $tm = $targeted->format('m'); 
        $tY = $targeted->format('Y'); $tYI = intval($tY);
        
        // start of work range
        $params['start'] = \DateTime::createFromFormat('dmY', "{$fd}{$tm}{$tY}");
        // first week
        $targetedWeekStart = $targeted->setISODate($tYI, $twI);
        
        if($targetedWeekStart->format('d') != $fd) {
            $targetedWeekStart = $targetedWeekStart->sub(new \DateInterval('P0Y0M7D'));
        }
        
        $params['firstWeekStart'] = $targetedWeekStart;
        $params['firstWeek'] = intval($targetedWeekStart->format('W'));
        
        // firstWeek first week of range start
        $params['end']         = \DateTime::createFromFormat('dmY', $params['start']->format('tmY'));
        $params['lastWeek']    = intval($params['end']->format('W'));
        $dLastWeek             = \DateTimeImmutable::createFromFormat('dmY', $params['end']->format('dmY'));
        $dLastWeekStart        = $dLastWeek->setISODate($params['end']->format('Y'), $params['lastWeek']);
        $params['lastWeekEnd'] = $dLastWeekStart->add(new \DateInterval('P0Y0M6D'));
        
        return $params;
    }

    /**
     * Set display parameters for table generations and paginator
     * @todo change params['.. lines to some array_merging method
     * 
     * @param array $params
     * @return array
     */
    protected function defaultsDisplayParameters(Array $params) : Array {
        $params['mode'] = $params['mode'] ?? "month";
        
        $now = New \DateTime();
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
                $dts = new \DateTimeImmutable();
                $params['start'] = $dts->setISODate($params['year'], $params['week']);
                // @todo change 'P0Y0M6D' and "2812" to some constant or parameters
                $params['end']      = $params['start']->add(new \DateInterval('P0Y0M6D'));
                $lastYearDayMinus   = \DateTime::createFromFormat('dmY', "2812" . $params['year'] -1);
                $lastYearDay        = \DateTime::createFromFormat('dmY', "2812{$params['year']}");
                $params['maxWeeksMinus'] = $lastYearDayMinus->format('W');
                $params['maxWeeks'] = $lastYearDay->format('W');
                break;
            
            case 'day':
                // @todo change 'dmY' to some constant or parameters
                $params['start'] = \DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
                $params['end'] = $params['start'];
                break;
        }
        
        $s = $s ?? $params['start'];
        $e = $e ?? $params['end'];
            
        $params['dates'] = $this->getDates(['start' => $s, 'end' => $e]);
        
        return $params;
    }
    
    /**
     * Display one worktrack agenda by id and in a selected mode with paginate
     * 
     * @Route("/worktime_tracker/{id}/{mode?}/{year<\d+>?0}/{month<\d+>?0}/{week<\d+>?0}/{day<\d+>?0}", name="worktime_tracker_display")
     * @IsGranted("ROLE_USER")
     * 
     * @param Agenda $agenda
     * @param User|null $u
     * @param string|null $mode
     * @param int|null $year
     * @param int|null $month
     * @param int|null $week
     * @param int|null $day
     * @return Response
     */
    public function display(Agenda $agenda, ?User $u = null, ?string $mode = null, ?int $year = null, ?int $month = null, ?int $week = null, ?int $day = null): Response {
        $params = $this->defaultsDisplayParameters(['mode' => $mode, 'year' => $year, 'month' => $month, 'week' => $week, 'day' => $day]);
        $params = $this->selectedDateFromParams($params);
        
        /* @var $user UserInterface */
        $user = $u ?? $this->getUser();
        $this->denyAccessUnlessGranted('read', $agenda);
        
        return $this->render('work_time_tracker/display.html.twig',  array_merge([
                'agenda' => $agenda,
            ], 
            $this->getCommonVariables($user, $agenda),
            ['params' => $params]
        ));
    }
    
}
