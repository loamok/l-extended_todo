<?php

namespace App\Controller;

use App\Entity\Agenda;
use App\Form\AgendaFormType;
use App\Repository\AgendaRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class WorkTimeTrackerController extends AbstractController {
    
    protected function userAgendas(UserInterface $user) {
        /* @var $agendaRep AgendaRepository */
        $agendasRep = $this->getDoctrine()->getManager()->getRepository(Agenda::class);
        return $agendasRep->getUserAgendasByUserRightCodeAndType($user, 'list', "work_track");
    }
    
    /**
     * @Route("/worktime_tracker", name="worktime_tracker")
     * @IsGranted("ROLE_USER")
     */
    public function index(UserInterface $user): Response {
        
        return $this->render('work_time_tracker/index.html.twig', [
            'controller_name' => 'WorkTimeTrackerController',
            'agendas' => $this->userAgendas($user),
        ]);
    }
    
    /**
     * @Route("/worktime_tracker/create", name="worktime_tracker_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(UserInterface $user, Request $request): Response {
        $agenda = new Agenda();
        $form = $this->createForm(AgendaFormType::class, $agenda);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($agenda);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('worktime_tracker_display', ['id' => $agenda->getId()]);
        }
        
        return $this->render('work_time_tracker/create.html.twig', [
            'agendas' => $this->userAgendas($user),
            'agenda_form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/worktime_tracker/{id}", name="worktime_tracker_display")
     * @IsGranted("ROLE_USER")
     */
    public function display(UserInterface $user, Agenda $agenda): Response {
        
        return $this->render('work_time_tracker/display.html.twig', [
            'agendas' => $this->userAgendas($user),
            'agenda' => $agenda
        ]);
    }
}
