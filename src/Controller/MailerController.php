<?php

// src/Controller/MailerController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController {
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer) {
        $email = (new Email())
            ->from('no-reply@loamok.org')
            ->to('franck.huby@loamok.org')
            ->cc('franck.huby@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        // ...
    }
}