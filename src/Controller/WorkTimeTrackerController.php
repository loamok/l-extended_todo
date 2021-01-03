<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Entity\WtParameters;
use App\Form\AgendaFormType;
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

    protected function getCommonVariables(UserInterface $user, ?Agenda $agenda = null, ?WtParameters $params = null) {
        $agenda = $agenda ?? new Agenda();
        $this->denyAccessUnlessGranted('list', $agenda);
        $params = $params ?? new WtParameters();
        $this->denyAccessUnlessGranted('list', $params);
        
        $emptyParams = new WtParameters();
        $epForm = $this->createForm(\App\Form\WtParametersType::class, $emptyParams);
        
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
    public function index(UserInterface $user): Response {
        
        return $this->render('work_time_tracker/index.html.twig', array_merge([
            'controller_name' => 'WorkTimeTrackerController',
        ], $this->getCommonVariables($user)));
    }
    
    /**
     * @Route("/worktime_tracker/create", name="worktime_tracker_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(UserInterface $user, Request $request): Response {
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
    
    /**
     * @Route("/worktime_tracker/{id}", name="worktime_tracker_display")
     * @IsGranted("ROLE_USER")
     */
    public function display(UserInterface $user, Agenda $agenda): Response {
        $this->denyAccessUnlessGranted('read', $agenda);
        
        return $this->render('work_time_tracker/display.html.twig',  array_merge([
                'agenda' => $agenda,
            ], 
            $this->getCommonVariables($user, $agenda)
        ));
    }
}
