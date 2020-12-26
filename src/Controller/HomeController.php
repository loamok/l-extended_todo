<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class HomeController extends AbstractController {
    
    /**
     * @Route("/home", name="home")
     * @Route("/", name="app_homepage")
     * @Route("/", name="app_home")
     */
    public function index(): Response {
        /* @var $user \App\Entity\User */
        $user = $this->getUser();
        if(!is_null($user)) {
            dump($user);
            dump($user->getId());
            dump($user->__toString());
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /** 
     * @Route("/plop", name="app_plop")
     * @IsGranted("ROLE_USER")
     */
    public function plop(): Response {
        
        return $this->render('home/plop.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
