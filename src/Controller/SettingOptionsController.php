<?php

namespace App\Controller;

use App\Entity\SettingOptions;
use App\Entity\FastConsultation;
use App\Form\SettingOptionsType;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\SavingMailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SettingOptionsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/setting/options')]
class SettingOptionsController extends AbstractController
{
    #[Route('/', name: 'app_setting_options_index', methods: ['GET'])]
    public function index(SettingOptionsRepository $settingOptionsRepository, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('setting_options/index.html.twig', [
            'setting_options' => $settingOptionsRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'title' => 'settings options',
            'controller_name' => 'Настройки сети',
        ]);
    }

    #[Route('/new', name: 'app_setting_options_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SettingOptionsRepository $settingOptionsRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $settingOption = new SettingOptions();
        $form = $this->createForm(SettingOptionsType::class, $settingOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingOptionsRepository -> setCreatedAt(new \DateTime());
            $settingOptionsRepository->add($settingOption);
            return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('setting_options/new.html.twig', [
            'setting_option' => $settingOption,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'new settings options',
            'controller_name' => 'Добавить настройки сети',
        ]);
    }

    #[Route('/{id}', name: 'app_setting_options_show', methods: ['GET'])]
    public function show(SettingOptions $settingOption, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('setting_options/show.html.twig', [
            'setting_option' => $settingOption,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'title' => 'settings options',
            'controller_name' => 'Настройки сети',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_setting_options_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SettingOptions $settingOption, SettingOptionsRepository $settingOptionsRepository,  EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $form = $this->createForm(SettingOptionsType::class, $settingOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $settingOptionsRepository -> setUpdatedAt(new \DateTime());
            $settingOptionsRepository->add($settingOption);
            return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('setting_options/edit.html.twig', [
            'setting_option' => $settingOption,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'title' => 'edit settings options',
            'controller_name' => 'Редактировать настройки сети',
        ]);
    }

    #[Route('/{id}', name: 'app_setting_options_delete', methods: ['POST'])]
    public function delete(Request $request, SettingOptions $settingOption, SettingOptionsRepository $settingOptionsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$settingOption->getId(), $request->request->get('_token'))) {
            $settingOptionsRepository->remove($settingOption);
        }

        return $this->redirectToRoute('app_setting_options_index', [], Response::HTTP_SEE_OTHER);
    }
}
