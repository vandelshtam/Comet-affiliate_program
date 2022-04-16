<?php

namespace App\Controller;

use App\Entity\TablePakage;
use App\Form\TablePakageType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TablePakageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/table/pakage')]
class TablePakageController extends AbstractController
{
    #[Route('/', name: 'app_table_pakage_index', methods: ['GET'])]
    public function index(TablePakageRepository $tablePakageRepository,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('table_pakage/index.html.twig', [
            'controller_name' => 'Страница обзора пакетов',
            'table_pakages' => $tablePakageRepository->findAll(),
            'title' => 'Pakages',
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/new/admin', name: 'app_table_pakage_new_admin', methods: ['GET', 'POST'])]
    public function new(TablePakageRepository $tablePakageRepository,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $tablePakage = new TablePakage();
        $form = $this->createForm(TablePakageType::class, $tablePakage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tablePakageRepository -> setCreatedAt(new \DateTime());
            $tablePakageRepository->add($tablePakage);
            return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('table_pakage/new.html.twig', [
            'table_pakage' => $tablePakage,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_table_pakage_show', methods: ['GET'])]
    public function show(TablePakage $tablePakage,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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

        return $this->render('table_pakage/show.html.twig', [
            'table_pakage' => $tablePakage,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_table_pakage_edit_admin', methods: ['GET', 'POST'])]
    public function edit(Request $request, TablePakage $tablePakage, TablePakageRepository $tablePakageRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(TablePakageType::class, $tablePakage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tablePakageRepository -> setUpdatedAt(new \DateTime());
            $tablePakageRepository->add($tablePakage);
            return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('table_pakage/edit.html.twig', [
            'table_pakage' => $tablePakage,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/admin', name: 'app_table_pakage_delete_admin', methods: ['POST'])]
    public function delete(Request $request, TablePakage $tablePakage, TablePakageRepository $tablePakageRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$tablePakage->getId(), $request->request->get('_token'))) {
            $tablePakageRepository->remove($tablePakage);
        }

        return $this->redirectToRoute('app_table_pakage_index', [], Response::HTTP_SEE_OTHER);
    }
}
