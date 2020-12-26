<?php

namespace App\Security;

use App\Entity\Agenda;
use App\Entity\Delegation;
use App\Entity\DelegationType;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

/**
 * Description of Delegations
 *
 * @author symio
 */
class Delegations implements EventSubscriber {
    
    const delegationType_proprietary = "proprietary";

    protected $security;
    
    public function __construct(Security $security){
        $this->security = $security;
    }
    
    public function getSubscribedEvents() {
        return [ 'postPersist', 'postUpdate', ];
    }

    public function postUpdate(LifecycleEventArgs $args) {
//        $this->delegate($args);
    }

    public function postPersist(LifecycleEventArgs $args) {
        $this->delegate($args);
    }

    protected function delegate(LifecycleEventArgs $args, bool $selfOwner = false, ?\App\Entity\User $user = null) {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
//        dump($user);
        if ($entity instanceof Agenda) {
            $owner = $this->security->getUser();
            if(!$selfOwner && is_null($user)) {
                $user = $this->security->getUser();
            }
            $delegation = $em->getRepository(Delegation::class)->findOneBy(['agenda' => $entity, 'user' => $user]);
            if(is_null($delegation)) {
                $delegation = new Delegation();
            }
            $delegationType = $em->getRepository(DelegationType::class)->findOneBy(['code' => self::delegationType_proprietary]);
            $delegation
                    ->setAgenda($entity)
                    ->setOwner($owner)
                    ->setUser($user)
                    ->setDelegationType($delegationType)
            ;
            $em->persist($delegation);
            $em->flush();
        }
    }
    
}
