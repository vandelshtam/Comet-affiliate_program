<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FastConsultationController extends AbstractController
{
    #[Route('/fast/consultation', name: 'app_fast_consultation')]
    public function index(): Response
    {
        dd('консультация');
        return $this->render('fast_consultation/index.html.twig', [
            'controller_name' => 'FastConsultationController',
        ]);
    }

    public function fastSendMeil(Request $request,MailerInterface $mailer, $fast_consultation, MailerController $mailerController, $entityManager, $textSendMail,$email_client)
    {
        //$entityManager->persist($fast_consultation);
        //$entityManager->flush();
        $mailerController->sendFastConsultationEmail($mailer,$fast_consultation,$textSendMail,$email_client);
    }

    // public function fastConsultationMethod(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController)
    // {
    //     $fast_consultation = new FastConsultation();       
    //     $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
    //     $fast_consultation_form->handleRequest($request);
    //     return $fast_consultation_form;
    // }
}
