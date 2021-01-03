<?php

namespace App\WorkTrackServices;

use App\Entity\Agenda;
use App\Entity\User;
use App\Entity\WtParameters;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Description of WtParametersService
 *
 * @author symio
 */
class WtParametersService {
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
    }
    
    public function findGlobalParamsForUser(User $user) {
        return $this->em->getRepository(WtParameters::class)->findGlobalParamForUser($user);
    }
    public function getParamsForUserAndAgenda(User $user, ?Agenda $agenda = null) {
        return $this->em->getRepository(WtParameters::class)->getParamsForUserAndAgenda($user, $agenda);
    }
}
