<?php

namespace App\WorkTrackServices;

use App\Entity\Agenda;
use App\Entity\Category;
use App\Entity\DayParameters;
use App\Entity\Event;
use App\Entity\FbType;
use App\Entity\Freebusy;
use App\Entity\Related;
use App\Entity\RelType;
use App\Entity\Status;
use App\Entity\Todo;
use App\Entity\WtParameters;
use App\Repository\AgendaRepository;
use App\Repository\EventRepository;
use App\Repository\TodoRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * WtViewPrepare Service for preparing a view content
 *
 * @author symio
 */
class WtViewPrepare {
    
    /**
     * 
     * @var Security
     */
    protected $security;
    /**
     * 
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * 
     * @var AgendaService
     */
    protected $as;
    /**
     * 
     * @var WtParametersService
     */
    protected $wttPs;
    /**
     * 
     * @var WTDaysRangeCalculator
     */
    protected $wDrCalc;

    /**
     * 
     * @param Security $security
     * @param EntityManagerInterface $em
     * @param AgendaService $as
     * @param WtParametersService $wttPs
     * @param WTDaysRangeCalculator $wDrCalc
     */
    public function __construct(Security $security, EntityManagerInterface $em, AgendaService $as, WtParametersService $wttPs, WTDaysRangeCalculator $wDrCalc) {
        $this->security = $security;
        $this->em = $em;
        $this->as = $as;
        $this->wttPs = $wttPs;
        $this->wDrCalc = $wDrCalc;
        
    }
    
    /**
     * Set parameters and get needed objects
     * 
     * @param array $params
     * @return void
     */
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
        
        $params['wtParametersObj'] = $this->em->getRepository(WtParameters::class)->find($params['wtParameters']);
        $params['dayParametersObj'] = $this->em->getRepository(DayParameters::class)->find($params['dayParameters']);
        /* @var $status Status */
        $params['statusObj'] = $this->em->getRepository(Status::class)->findOneBy(['code' => 'draft']);
        
    }
    
    /**
     * 
     * @param type $params
     * @param ServiceEntityRepository $repo
     * @return void
     */
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
            $params['elemsInRange']["{$esdid}"] = ($eType == 'todo') ? $this->fromTodoToRelateds($params, $elem) : ['type' => $eType, 'object' => $elem];
        }
        
    }
    
    /**
     * 
     * @param Todo $todo
     * @param array $def
     * @return Freebusy
     */
    protected function addFreeFbToTodo(Todo &$todo, array $def) : Freebusy {
        /* @var $freetimeType FbType */
        $freetimeType = $this->em->getRepository(FbType::class)->findOneBy(['code' => "free"]);
        /* @var $relType RelType */
        $relType = $this->em->getRepository(RelType::class)->findOneBy(['code' => "child"]);
        
        $freeFb = new Freebusy();
        $freeFb->setAgenda($todo->getAgenda());
        $freeFb->setStartAt($def['start']);
        $freeFb->setEndAt($def['end']);
        $freeFb->setType($freetimeType);
        $freeFb->addCategory($this->em->getRepository(Category::class)->findOneBy(['code' => $def['type']]));
        $this->em->persist($freeFb);
        
        $r = new Related();
        $r->setType($relType);
        $r->setAgenda($todo->getAgenda());
        $r->setFreebusy($freeFb);
        $r->setParent($todo);
        $this->em->persist($r);
        
        return $freeFb;
    }
    
    /**
     * 
     * @param string $name
     * @param DateTimeInterface $baseTime
     * @param DateTimeInterface $start
     * @param DateTimeInterface $end
     * @param string $timezone
     * @return array
     */
    protected function setBreak(string $name, DateTimeInterface $baseTime, DateTimeInterface $start, DateTimeInterface $end, string $timezone) : array {
        
        return [
            'type' => $name,
            'start' => DateTimeImmutable::createFromFormat(DateTimeImmutable::RFC3339_EXTENDED, 
                $baseTime->format('Y-m-d\T').$start->format('H:i:s.v').$timezone
            ),
            'end' => DateTimeImmutable::createFromFormat(DateTimeImmutable::RFC3339_EXTENDED, 
                $baseTime->format('Y-m-d\T').$end->format('H:i:s.v').$timezone
            ),
        ];
    }
    
    /**
     * 
     * @param array $params
     * @param Todo $todo
     * @return array
     */
    protected function fromTodoToRelateds(array $params, Todo $todo) : array {
        $relateds = $todo->getRelateds()->toArray();
        
        /* @var $workCat Category */
        $workCat = $this->em->getRepository(Category::class)->findOneBy(['code' => "worktime"]);
        if((empty($relateds) || is_null($relateds) || count($relateds) == 0) && $todo->hasCategory($workCat)) {
            $pauses = [
                'am' => $this->setBreak('am-break', $todo->getStartAt(), $params['dayParametersObj']->getAmPauseStart(), 
                    $params['dayParametersObj']->getAmPauseEnd(), $todo->getStartAt()->getTimezone()->getName()),
                'meridian' => $this->setBreak("meridian-break", $todo->getStartAt(), $params['dayParametersObj']->getAmEnd(), 
                    $params['dayParametersObj']->getPmStart(), $todo->getStartAt()->getTimezone()->getName()),
                'pm' => $this->setBreak("pm-break", $todo->getStartAt(), $params['dayParametersObj']->getPmPauseStart(), 
                    $params['dayParametersObj']->getPmPauseEnd(), $todo->getStartAt()->getTimezone()->getName()),
            ];
            $relateds = [];
            
            foreach ($pauses as $pause) {
                $this->addFreeFbToTodo($todo, $pause);
            }
        }
        
        $this->em->flush();
        $this->em->refresh($todo);
        
        return ['type' => 'todo', 'object' => $todo, 'relateds' => new ArrayCollection(array_merge($relateds, $todo->getBreaks()))];
    }
    
    /**
     * 
     * @param array $params
     * @param DateTimeInterface $day
     * @return Todo|null
     */
    protected function createTodo(array &$params, DateTimeInterface $day) : ?Todo {
        $todo = new Todo();
        /* @var $workCat Category */
        $workCat = $this->em->getRepository(Category::class)->findOneBy(['code' => "worktime"]);
        
        $todo->setAgenda($params['agendaObj']);
        $start = DateTimeImmutable::createFromFormat(DateTimeImmutable::RFC3339_EXTENDED, 
                $day->format('Y-m-d')."T".$params['dayParametersObj']->getAmStart()->format('H:i:s.v')."+01:00");
        $todo->setStartAt($start);
        $end = DateTimeImmutable::createFromFormat(DateTimeImmutable::RFC3339_EXTENDED, 
                $day->format('Y-m-d')."T".$params['dayParametersObj']->getPmEnd()->format('H:i:s.v')."+01:00");
        $todo->setEndAt($end);
        
        $todo->setSummary("Generated");
        $todo->setStatus($params['statusObj']);
        $todo->addCategory($workCat);
        
        $this->em->persist($todo);
        
        return $todo;
    }
    
    /**
     * 
     * @param array $params
     * @param bool|null $autoCreate
     */
    protected function completeElems(array &$params, ?bool $autoCreate = true) {
        /* @var $week array */
        foreach ($params['calculatedRange']['dates'] as $week) {
            /* @var $day DateTimeInterface */
            foreach ($week as $day) {
                $did = $day->format('Ymd');
                if(!array_key_exists($did, $params['elemsInRange']) && $autoCreate) {
                    $params['elemsInRange']["{$did}"] = $this->fromTodoToRelateds($params, $this->createTodo($params, $day));
                }
            }
        }
        
    }
    
    /**
     * Prepare the range for workTrack
     * 
     * @param array $params
     * @param bool|null $autoCreate
     * @return array
     */
    public function prepareMe(array $params, ?bool $autoCreate = true) : array {
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
        
        $this->completeElems($params, $autoCreate);
        $this->em->flush();
        
        return $params;
    }
    
}
