<?php

namespace App\WorkTrackServices;

use App\Entity\Agenda;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Description of AgendaService
 *
 * @author symio
 */
class AgendaService {
    
    protected $security;
    protected $em;

    public function __construct(Security $security, EntityManagerInterface $em) {
        $this->security = $security;
        $this->em = $em;
    }
    
    public function getWTAgendasForUser(User $user) {
        return $this->em->getRepository(Agenda::class)->getUserAgendasByUserRightCodeAndType($user, 'list', "work_track");
    }
}
