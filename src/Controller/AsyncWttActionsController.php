<?php

namespace App\Controller;

use App\WorkTrackServices\WtViewPrepare;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class AsyncWttActionsController extends AbstractController {
    
    /**
     * @Route("/wtt_actions/prepare", methods={"POST"}, options = { "expose" = true }, name="async_wtt_actions_prepare")
     * @IsGranted("ROLE_USER")
     */
    public function prepare(Request $request, UserInterface $user, WtViewPrepare $wVps): Response {
        $requestParams = $request->toArray();
        $parameters = [
            'wtParameters' => $requestParams['wtParameters'],
            'dayParameters' => $requestParams['dayParameters'],
            'agenda' => $requestParams['agenda'],
            'mode' => $requestParams['mode'],
            'user' => $user,
        ];
        
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AsyncWttActionsController.php',
            'params' => $wVps->prepareMe($parameters),
        ]);
    }
    
}
