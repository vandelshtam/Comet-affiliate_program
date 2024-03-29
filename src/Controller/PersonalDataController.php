<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Wallet;

use App\Entity\PersonalData;
use App\Form\PersonalDataType;
use App\Entity\FastConsultation;
use App\Form\EditPersonalDataType;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\WalletRepository;
use App\Repository\SavingMailRepository;
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
    #[Route('/admin', name: 'app_personal_data_index', methods: ['GET'])]
    public function index(Request $request, PersonalDataRepository $personalDataRepository,ManagerRegistry $doctrine,EntityManagerInterface $entityManager, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('personal_data/index.html.twig', [
            'personal_datas' => $personalDataRepository->findAll(),
            'title' => 'Personal data',
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'controller_name' => 'Все персональные данные',
            'title' => 'Personal data all',
        ]);
    }

    #[Route('/{user_id}/new', name: 'app_personal_data_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PersonalDataRepository $personalDataRepository, WalletRepository $walletRepository,ManagerRegistry $doctrine,EntityManagerInterface $entityManager, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $personal_data_id = $this->getUser()->getPersonalDataId();
        if($personal_data_id != NULL || $user_id != $user -> getId())
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $personalDatum = new PersonalData();
        $wallet = new Wallet();
        $form = $this->createForm(PersonalDataType::class, $personalDatum);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDatum -> setCreatedAt(new \DateTime());
            $personalDataRepository->add($personalDatum);
            $wallet -> setUser($user);
            $wallet -> setUsdt(0);
            $wallet -> setBitcoin(0);
            $wallet -> setCometpoin(0);
            $wallet -> setEtherium(0);
            $wallet -> setCreatedAt(new \DateTime());
            $walletRepository->add($wallet);

            $personalData = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
            $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user -> getId()]);
            $user_table->setPersonalDataId($personalData -> getId());
            $random_code = 'CP'.mt_rand();
            $client_code = $user -> getId().$random_code;
            $secret_code = mt_rand().'-'.mt_rand();
            $user_table->setPesonalCode($client_code);
            $user_table->setSecretCode($secret_code);
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
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/new.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'user_id' => $user_id,
            'new_user_make' => true,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Страница регистрации персональных данных',
            'title' => 'Personal data new',
        ]);
    }

    
    #[Route('/{personal_user_id}/show', name: 'app_personal_data_show', methods: ['GET', 'POST'])]
    public function show(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository,int $personal_user_id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(PersonalData::class);
        
        if($personal_user_id != NULL)
        {
            if($repository->findOneBy(['id' => $personal_user_id]) == NULL)
            {   
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/show.html.twig', [
            'personal_datum' => $personalDatum,
            'user' => $user,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Страница персональных данных',
            'title' => 'Personal data show',
        ]);
    }

    #[Route('/{id}/edit', name: 'app_personal_data_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $repository = $doctrine->getRepository(PersonalData::class);

        if($this->getUser()->getPersonalDataId() != NULL)
        {
            if($repository->findOneBy(['id' => $this->getUser()->getPersonalDataId()]) == NULL)
            {   
                $this->denyAccessUnlessGranted('ROLE_ADMIN');
            }
        }
        else{
            $this->addFlash(
                'danger',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }


        if($this->getUser()->getPersonalDataId() != $id)
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $user = $this->getUser();
        $form = $this->createForm(EditPersonalDataType::class, $personalDatum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $personalDatum -> setUpdatedAt(new \DateTimeImmutable());
            $personalDataRepository->add($personalDatum);
            return $this->redirectToRoute('app_personal_data_show', ['personal_user_id' => $id], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_data/editPersonalData.html.twig', [
            'personal_datum' => $personalDatum,
            'form' => $form,
            'new_user_make' => false,
            'user_id' => $user->getId(),
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Страница редактирование персональных данных',
            'title' => 'Personal data edit',
        ]);
    }

    #[Route('/{id}/delete', name: 'app_personal_data_delete', methods: ['POST'])]
    public function delete(Request $request, PersonalData $personalDatum, PersonalDataRepository $personalDataRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$personalDatum->getId(), $request->request->get('_token'))) {
            $personalDataRepository->remove($personalDatum);
        }

        return $this->redirectToRoute('app_personal_data_index', [], Response::HTTP_SEE_OTHER);
    }
}
