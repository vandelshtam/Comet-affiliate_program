<?php

namespace App\Controller;

use App\Entity\TokenRate;
use App\Form\TokenRateType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\TokenRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/token/rate')]
class TokenRateController extends AbstractController
{
    #[Route('/', name: 'app_token_rate_index', methods: ['GET'])]
    public function index(TokenRateRepository $tokenRateRepository,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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

        return $this->render('token_rate/index.html.twig', [
            'token_rates' => $tokenRateRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_token_rate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TokenRateRepository $tokenRateRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $tokenRate = new TokenRate();
        $form = $this->createForm(TokenRateType::class, $tokenRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tokenRateRepository -> setCreatedAt(new \DateTime());
            $tokenRateRepository->add($tokenRate);
            return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('token_rate/new.html.twig', [
            'token_rate' => $tokenRate,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_token_rate_show', methods: ['GET'])]
    public function show(TokenRate $tokenRate,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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
        return $this->render('token_rate/show.html.twig', [
            'token_rate' => $tokenRate,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_token_rate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TokenRate $tokenRate, TokenRateRepository $tokenRateRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $form = $this->createForm(TokenRateType::class, $tokenRate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tokenRateRepository -> setUpdatedAt(new \DateTime());
            $tokenRateRepository->add($tokenRate);
            return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('token_rate/edit.html.twig', [
            'token_rate' => $tokenRate,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_token_rate_delete', methods: ['POST'])]
    public function delete(Request $request, TokenRate $tokenRate, TokenRateRepository $tokenRateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tokenRate->getId(), $request->request->get('_token'))) {
            $tokenRateRepository->remove($tokenRate);
        }

        return $this->redirectToRoute('app_token_rate_index', [], Response::HTTP_SEE_OTHER);
    }
}
