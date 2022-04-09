<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Кабинет администратора',
            'title' => 'Admin',
            'fast_consultation_form' => $fast_consultation_form -> createView(),
        ]);
    }
}
