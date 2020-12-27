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

    public function delegate(LifecycleEventArgs $args, ?\App\Entity\User $user = null, ?\App\Entity\User $owner = null, string $type = self::delegationType_proprietary) {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();
        if ($entity instanceof Agenda) {
            if(is_null($user)) {
                $owner = $this->security->getUser();
            }
            if(is_null($user)) {
                $user = $this->security->getUser();
            }
            $delegation = $em->getRepository(Delegation::class)->findOneBy(['agenda' => $entity, 'user' => $user, 'owner' => $owner]);
            if(is_null($delegation)) {
                $delegation = new Delegation();
            }
            $delegationType = $em->getRepository(DelegationType::class)->findOneBy(['code' => $type]);
            $delegation->setAgenda($entity)->setOwner($owner)
                    ->setUser($user)->setDelegationType($delegationType);
            $em->persist($delegation);
            $em->flush();
        }
    }
    
}
