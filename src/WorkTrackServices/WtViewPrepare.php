<?php

namespace App\WorkTrackServices;

use App\Entity\Agenda;
use App\Entity\DayParameters;
use App\Entity\Event;
use App\Entity\Status;
use App\Entity\Todo;
use App\Entity\WtParameters;
use App\Repository\AgendaRepository;
use App\Repository\DayParametersRepository;
use App\Repository\EventRepository;
use App\Repository\StatusRepository;
use App\Repository\TodoRepository;
use App\Repository\WtParametersRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    
    protected function setParams(array &$params) : void {
        if(array_key_exists('paginateParams', $params)) {
            $params['paginateParams']['year']  = (array_key_exists('year',  $params['paginateParams'])) ? intval($params['paginateParams']['year'])  : 0;
            $params['paginateParams']['month'] = (array_key_exists('month', $params['paginateParams'])) ? intval($params['paginateParams']['month']) : 0;
            $params['paginateParams']['week']  = (array_key_exists('week',  $params['paginateParams'])) ? intval($params['paginateParams']['week'])  : 0;
            $params['paginateParams']['day']   = (array_key_exists('day',   $params['paginateParams'])) ? intval($params['paginateParams']['day'])   : 0;
        }
        
        $params['calculatedRange'] = $this->wDrCalc->selectedDateFromParams($this->wDrCalc->defaultsDisplayParameters($params['paginateParams']));
        
        $params['elemsInRange'] = [];
        /* @var $start DateTimeInterface */
        $start = $params['calculatedRange']['firstWeekStart'] ?? $params['calculatedRange']['start'];
        /* @var $end DateTimeInterface */
        $end   = $params['calculatedRange']['lastWeekEnd']    ?? $params['calculatedRange']['end'];
        $params['boundaries'] = [
            'start' => DateTimeImmutable::createFromFormat('Ymd-His', $start->format('Ymd')."-000000"),
            'end' => DateTimeImmutable::createFromFormat('Ymd-His', $end->format('Ymd')."-235959"),
        ];
        
        /* @var $paramRep WtParametersRepository */
        $paramRep = $this->em->getRepository(WtParameters::class);
        /* @var $wtParam WtParameters */
        $wtParam = $paramRep->find($params['wtParameters']);
        /* @var $dayParamRep DayParametersRepository */
        $dayParamRep = $this->em->getRepository(DayParameters::class);
        /* @var $dayParam DayParameters */
        $dayParam = $dayParamRep->find($params['dayParameters']);
        /* @var $statusRep StatusRepository */
        $statusRep = $this->em->getRepository(Status::class);
        /* @var $status Status */
        $status = $statusRep->findOneBy(['code' => 'draft']);
        
        $params['wtParametersObj'] = $wtParam;
        $params['dayParametersObj'] = $dayParam;
        $params['statusObj'] = $status;
        
    }
    
    protected function getElements(&$params, ServiceEntityRepository $repo) {
        $eType = null;
        
        if(!is_a($repo, TodoRepository::class) && !is_a($repo, EventRepository::class)) {
            return;
        } elseif (is_a($repo, TodoRepository::class)) {
            $eType = "todo";
        } elseif (is_a($repo, EventRepository::class)) {
            $eType = "event";
        }
        
        
        foreach ($repo->getFromAgendaInRange($params['agendaObj'], $params['boundaries']) as $elem) {
            if($elem->isDeleted()) {
                continue;
            }
            
            $esdid = $elem->getStartAt()->format('Ymd');
            $params['elemsInRange']["{$esdid}"] = ['type' => $eType, 'object' => $elem];
        }
    }
    
    protected function createTodo(array &$params, DateTimeInterface $day) : ?Todo {
        $todo = new Todo();
        
        $todo->setAgenda($params['agendaObj']);
        $start = DateTimeImmutable::createFromFormat(
                DateTimeImmutable::RFC3339_EXTENDED, 
                $day->format('Y-m-d')."T".$params['dayParametersObj']->getAmStart()->format('H:i:s.v')."+01:00");
        $todo->setStartAt($start);
        $end = DateTimeImmutable::createFromFormat(
                DateTimeImmutable::RFC3339_EXTENDED, 
                $day->format('Y-m-d')."T".$params['dayParametersObj']->getPmEnd()->format('H:i:s.v')."+01:00");
        $todo->setEndAt($end);
        
        $todo->setSummary("Generated");
        $todo->setStatus($params['statusObj']);
        
        $this->em->persist($todo);
        return $todo;
    }
    
    protected function completeElems(array &$params) {
        /* @var $week array */
        foreach ($params['calculatedRange']['dates'] as $week) {
            /* @var $day DateTimeInterface */
            foreach ($week as $day) {
                $did = $day->format('Ymd');
                if(!array_key_exists($did, $params['elemsInRange'])) {            
                    $params['elemsInRange']["{$did}"] = ['type' => "todo", 'object' => $this->createTodo($params, $day)];
                }
            }
        }
    }
    
    public function prepareMe(array $params) {
        $this->setParams($params);
        
        /* @var $agRep AgendaRepository */
        $agRep = $this->em->getRepository(Agenda::class);
        $agenda = $agRep->getOneAgendaForUser($params['user'], 'read', $params['agenda']);
        $params['agendaObj'] = $agenda;
        
        /* @var $todosRep TodoRepository */
        $todosRep = $this->em->getRepository(Todo::class);
        /* @var $eventsRep EventRepository */
        $eventsRep = $this->em->getRepository(Event::class);
        
        $this->getElements($params, $todosRep);
        $this->getElements($params, $eventsRep);
        
        $this->completeElems($params);
        $this->em->flush();
        return $params;
    }
}
