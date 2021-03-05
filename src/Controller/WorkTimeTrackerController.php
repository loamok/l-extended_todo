<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\WtParameters;
use App\Form\AgendaFormType;
use App\Form\WtParametersType;
use App\Repository\AgendaRepository;
use App\WorkTrackServices\AgendaService;
use App\WorkTrackServices\WTDaysRangeCalculator;
use App\WorkTrackServices\WtParametersService;
use App\WorkTrackServices\WtViewPrepare;
use DateInterval;
use DateTime;
use DateTimeImmutable;
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
     * 
     * @var WTDaysRangeCalculator
     */
    protected $wTDaysRangeCalculator;

    /**
     * Controller constructor
     * 
     * @param AgendaService $agendaSvc
     * @param WtParametersService $wtParamsSvc
     */
    public function __construct(AgendaService $agendaSvc, WtParametersService $wtParamsSvc, WTDaysRangeCalculator $wTDaysRangeCalculator) {
        $this->agendaSvc = $agendaSvc;
        $this->wtParamsSvc = $wtParamsSvc;
        $this->wTDaysRangeCalculator = $wTDaysRangeCalculator;
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
     * Display one worktrack agenda by id and in a selected mode with paginate
     * 
     * @Route("/worktime_tracker/{id}/{mode?}/{year<\d+>?0}/{month<\d+>?0}/{week<\d+>?0}/{day<\d+>?0}", name="worktime_tracker_display")
     * @IsGranted("ROLE_USER")
     * 
     * @param Agenda $agenda
     * @param WtViewPrepare $wVps
     * @param User|null $u
     * @param string|null $mode
     * @param int|null $year
     * @param int|null $month
     * @param int|null $week
     * @param int|null $day
     * @return Response
     */
    public function display(Agenda $agenda, WtViewPrepare $wVps, ?User $u = null, ?string $mode = null, ?int $year = null, ?int $month = null, ?int $week = null, ?int $day = null): Response {
        /* @var $user UserInterface */
        $user = $u ?? $this->getUser();
        $this->denyAccessUnlessGranted('read', $agenda);
        
        $params = $this->wTDaysRangeCalculator->defaultsDisplayParameters([
            'mode' => $mode, 'year' => $year, 'month' => $month, 'week' => $week, 'day' => $day
        ]);
        $params = $this->wTDaysRangeCalculator->selectedDateFromParams($params);
        $cv = $this->getCommonVariables($user, $agenda);
        $p = [ 'paginateParams' => [
                "mode"  => $params['mode'], "year"  => $params['selected']->format('Y'),
                "month" => $params['selected']->format('m'), "week"  => $params['selected']->format('W'),
                "day"   => $params['selected']->format('d')
            ],
            'dayParameters' => $cv['globalParam']->getDayParameters()->getId()->toRfc4122(), 'user' => $user,
            'agenda' => $agenda->getId()->toRfc4122(), 'wtParameters' => $cv['globalParam']->getId()->toRfc4122(),
        ];
        
        return $this->render('work_time_tracker/display.html.twig',  array_merge([
            'agenda' => $agenda, ], $cv, ['params' => $params, 'prepared' => $wVps->prepareMe($p, false)]
        ));
    }
    
}
