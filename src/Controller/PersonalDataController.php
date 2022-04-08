<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wallet;
use App\Entity\PersonalData;

use App\Form\PersonalDataType;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\PersonalDataRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personal/data')]
class PersonalDataController extends AbstractController
{
    #[Route('/', name: 'app_personal_data_index', methods: ['GET'])]
    public function index(PersonalDataRepository $personalDataRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('personal_data/index.html.twig', [
            'personal_datas' => $personalDataRepository->findAll(),
            'title' => 'Personal data',
        ]);
    }

    #[Route('/{user_id}/new', name: 'app_personal_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalDataRepository $personalDataRepository, WalletRepository $walletRepository,ManagerRegistry $doctrine,EntityManagerInterface $entityManager, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $personal_data_id = $this->getUser()->getPersonalDataId();
        if($personal_data_id != NULL)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $personalDatum = new PersonalData();
        $wallet = new Wallet();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            $wallet -> setUser($user);
            $walletRepository->add($wallet);
            $personalData = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
            $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user -> getId()]);
            $user_table->setPersonalDataId($personalData -> getId());
            $random_code = 'CP'.mt_rand();
            $client_code = $user -> getId().$random_code;
            $user_table->setPesonalCode($client_code);
            $personalData->setClientCode($client_code);
            $entityManager->persist($user_table);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно зарегистрировали персональные данные');
            $personal_data_id = $personalData -> getId();
            return $this->redirectToRoute('app_personal_data_show', ['personal_user_id' => $personal_data_id], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            //dd($fast_consultation->getName());
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/new.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'user_id' => $user_id,
            'new_user_make' => true,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    
    #[Route('/{personal_user_id}/show', name: 'app_personal_data_show', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,int $personal_user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();

        $repository = $doctrine->getRepository(PersonalData::class);
        //dd($repository->findOneBy(['user_id' => $personal_user_id]));
        if($personal_user_id != NULL)
        {

            if($repository->findOneBy(['id' => $personal_user_id]) == NULL)
            {
                
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
            
            //if($doctrine->getRepository(PersonalData::class)->findOneBy(['id' => $personal_user_id]) == NULL){
                // $this->addFlash(
                //     'danger',
                //     'У вас нет прав доступа');
            
                // return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
            }

        }
        else{

            $this->addFlash(
                'danger',
                'У вас нет прав доступа');
        
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
            
        //$repository = $doctrine->getRepository(PersonalData::class);
        $personalDatum = $doctrine->getRepository(PersonalData::class)->findOneBySomeField($user -> getId());
        
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            //dd($fast_consultation->getName());
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/show.html.twig', [
            'personal_datum' => $personalDatum,
            'user' => $user,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if($this->getUser()->getPersonalDataId() != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $user = $this->getUser();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDataRepository->add($personalDatum);
            return $this->redirectToRoute('app_personal_data_show', ['personal_user_id' => $id], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('personal_data/edit.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'new_user_make' => false,
            'user_id' => $user->getId(),
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_personal_data_delete', methods: ['POST'])]
    public function delete(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personalDatum->getId(), $request->request->get('_token'))) {
            $personalDataRepository->remove($personalDatum);
        }

        return $this->redirectToRoute('app_personal_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
