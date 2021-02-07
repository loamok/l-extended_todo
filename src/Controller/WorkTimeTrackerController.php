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

    public function __construct(AgendaService $agendaSvc, WtParametersService $wtParamsSvc) {
        $this->agendaSvc = $agendaSvc;
        $this->wtParamsSvc = $wtParamsSvc;
    }

    protected function getCommonVariables(?User $user = null, ?Agenda $agenda = null, ?WtParameters $params = null) {
        $user = $user ?? $this->getUser();
        $agenda = $agenda ?? new Agenda();
        $this->denyAccessUnlessGranted('list', $agenda);
        $params = $params ?? new WtParameters();
        $this->denyAccessUnlessGranted('list', $params);
        
        $emptyParams = new WtParameters();
        $epForm = $this->createForm(WtParametersType::class, $emptyParams);
        
        return [
            'agendas' => $this->agendaSvc->getWTAgendasForUser($user),
            'params' => $this->wtParamsSvc->getParamsForUserAndAgenda($user, $agenda),
            'globalParam' => $this->wtParamsSvc->findGlobalParamsForUser($user),
            'epForm' => $epForm,
            'epFormView' => $epForm->createView(),
        ];
    }
    
    /**
     * @Route("/worktime_tracker", name="worktime_tracker")
     * @IsGranted("ROLE_USER")
     */
    public function index(?User $user = null): Response {
        $user = $user ?? $this->getUser();
        return $this->render('work_time_tracker/index.html.twig', array_merge([
            'controller_name' => 'WorkTimeTrackerController',
        ], $this->getCommonVariables($user)));
    }
    
    /**
     * @Route("/worktime_tracker/create", name="worktime_tracker_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ?User $user = null): Response {
        $user = $user ?? $this->getUser();
        $agenda = new Agenda();
        $this->denyAccessUnlessGranted('create', $agenda);
        
        $form = $this->createForm(AgendaFormType::class, $agenda);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('worktime_tracker_display', ['id' => $agenda->getId()]);
        }
        
        return $this->render('work_time_tracker/create.html.twig',  array_merge([
                'agenda_form' => $form->createView(),
                'agenda' => $agenda
            ], 
            $this->getCommonVariables($user, $agenda)
        ));
    }
    
    protected function getDates($params) {
        $dates = [];
        $current = $params['start'];
        
        do {
            if(!empty($dates)) {
                $current = $current->add(new \DateInterval('P0Y0M1D'));
            }
            $dates[intval($current->format('W'))][] = $current;
        } while ($current != $params['end']);
        
        return $dates;
    }
    
    protected function paramsFromCalledParameters($params) {
        
        if(!is_null($params['month'])) {
            $params['month'] = $params['month'];
        } else {
            $mFromYear = \DateTime::createFromFormat('dmY', $now->format('dm') . $params['year']);
            $params['month'] = intval($mFromYear->format('m'));
        }
        $params['month'] = ($params['month'] >= 10) ? $params['month'] : "0{$params['month']}";
        
        if(!is_null($params['day'])) {
            $params['day'] = $params['day'];
        } else {
            $dFromYearMonth = \DateTime::createFromFormat('dmY', $now->format('d') . "{$params['month']}{$params['year']}");
            $params['day'] = intval($dFromYearMonth->format('d'));
        }
        $params['day'] = ($params['day'] >= 10) ? $params['day'] : "0{$params['day']}";
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

    protected function startAndEndForMonth($params) {
        $targeted = \DateTimeImmutable::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
        $fd = "01"; $fdI = intval($fd);
        $ld = $targeted->format('t'); $ldI = intval($ld);
        $td = $targeted->format('d'); $tdI = intval($td);
        $tw = $targeted->format('W'); $twI = intval($tw);
        $tm = $targeted->format('m'); $tmI = intval($tm);
        $tY = $targeted->format('Y'); $tYI = intval($tY);
        
        // start of work range
        $params['start'] = \DateTime::createFromFormat('dmY', "{$fd}{$tm}{$tY}");
        // first week
        $targetedWeekStart = $targeted->setISODate($tYI, $twI);
        $dfirstWeekStart = $targetedWeekStart->sub(new \DateInterval('P0Y0M7D'));
        $params['firstWeekStart'] = $dfirstWeekStart;
        $params['firstWeek'] = intval($dfirstWeekStart->format('W'));
        
        // firstWeek first week of range start
        $params['end'] = \DateTime::createFromFormat('dmY', $params['start']->format('tmY'));
        $params['lastWeek'] = intval($params['end']->format('W'));
        $dLastWeek = \DateTimeImmutable::createFromFormat('dmY', $params['end']->format('dmY'));
        $dLastWeekStart = $dLastWeek->setISODate($params['end']->format('Y'), $params['lastWeek']);
        $params['lastWeekEnd'] = $dLastWeekStart->add(new \DateInterval('P0Y0M6D'));
        
        
        return $params;
    }

    protected function defaultsDisplayParameters(Array $params) : Array {
        $params['mode'] = $params['mode'] ?? "month";
        
        $now = New \DateTime();
        $params['year'] = $params['year'] ?? intval($now->format('Y'));
        $params['month'] = $params['month'] ?? intval($now->format('m'));
        $params['week'] = $params['week'] ?? intval($now->format('W'));
        $params['day'] = $params['day'] ?? intval($now->format('d'));
        $params['current'] = $now;
        
        $params = $this->paramsFromCalledParameters($params);
        
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
                $params['end'] = $params['start']->add(new \DateInterval('P0Y0M6D'));
                $lastYearDayMinus = \DateTime::createFromFormat('dmY', "3112" . $params['year'] -1);
                $lastYearDay = \DateTime::createFromFormat('dmY', "3112{$params['year']}");
                $params['maxWeeksMinus'] = $lastYearDayMinus->format('W');
                $params['maxWeeks'] = $lastYearDay->format('W');
                break;
            
            case 'day':
                $params['start'] = \DateTime::createFromFormat('dmY', "{$params['day']}{$params['month']}{$params['year']}");
                $params['end'] = $params['start'];
                break;
        }
        
        $s = $s ?? $params['start'];
        $e = $e ?? $params['end'];
            
        dump($params); //exit();
        $params['dates'] = $this->getDates(['start' => $s, 'end' => $e]);
        
        return $params;
    }
    
    /**
     * @Route("/worktime_tracker/{id}/{mode?}/{year<\d+>?}/{month<\d+>?}/{week<\d+>?}/{day<\d+>?}", name="worktime_tracker_display")
     * @IsGranted("ROLE_USER")
     */
    public function display(Agenda $agenda, ?User $user = null, ?string $mode, ?int $year, ?int $month, ?int $week, ?int $day): Response {
        $params = $this->defaultsDisplayParameters(['mode' => $mode, 'year' => $year, 'month' => $month, 'week' => $week, 'day' => $day]);
        
        $user = $user ?? $this->getUser();
        $this->denyAccessUnlessGranted('read', $agenda);
        
        dump($params);
        
        return $this->render('work_time_tracker/display.html.twig',  array_merge([
                'agenda' => $agenda,
            ], 
            $this->getCommonVariables($user, $agenda),
            ['params' => $params]
        ));
    }
}
