<?php

namespace App\Controller;

use App\Entity\TransactionTable;
use App\Form\FastConsultationType;
use App\Entity\FastConsultation;
use App\Form\TransactionTableType;
use App\Controller\MailerController;
use App\Repository\SavingMailRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use App\Repository\TransactionTableRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/transaction/table')]
class TransactionTableController extends AbstractController
{
    #[Route('/', name: 'app_transaction_table_index', methods: ['GET'])]
    public function index(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index.html.twig', [
            'transaction_tables' => $transactionTableRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все тразакции',
            'title' => 'transaction_tables',
            'type' => $this -> typeTransactions(),
        ]);
    }

    #[Route('/all/addwallet', name: 'app_transaction_table_index_all_addwallet', methods: ['GET'])]
    public function indexAllAddWallet(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $add_wallet = $entityManager->getRepository(TransactionTable::class)->findByAddWalletField(24, 26);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_all_addwallet.html.twig', [
            'transaction_tables' => $add_wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все поплнения кошельков',
            'title' => 'transaction_tables_addwallet',
            'type' => $this -> typeTransactions(),
        ]);
    }


    #[Route('/all/withdrawal/to/wallet', name: 'app_transaction_table_index_withdrawal_to_wallet', methods: ['GET'])]
    public function indexWithdrawalToWallet(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $withdrawal_to_wallet = $entityManager->getRepository(TransactionTable::class)->findByWithdrawalWalletField(3);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_withdrawal_wallet.html.twig', [
            'transaction_tables' => $withdrawal_to_wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все переводы из линии на кошельки пользователей',
            'title' => 'transaction_tables_withdrawal_to_wallet',
            'type' => $this -> typeTransactions(),
        ]);
    }

    #[Route('/all/withdrawal/out/wallet', name: 'app_transaction_table_index_withdrawal_out_wallet', methods: ['GET'])]
    public function indexWithdrawalOutWallet(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $withdrawal_out_wallet = $entityManager->getRepository(TransactionTable::class)->findByWithdrawalWalletField(4);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_withdrawal_wallet.html.twig', [
            'transaction_tables' => $withdrawal_out_wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все выводы из системы',
            'title' => 'transaction_tables_withdrawal_out_wallet',
            'type' => $this -> typeTransactions(),
        ]);
    }

    #[Route('/all/user/{user_id}', name: 'app_transaction_table_index_user', methods: ['GET'])]
    public function indexAllUser(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository, int $user_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $users = $entityManager->getRepository(TransactionTable::class)->findByUserField($user_id);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_user.html.twig', [
            'transaction_tables' => $users,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все операции пользователя',
            'title' => 'transaction_tables_user',
            'type' => $this -> typeTransactions(),
        ]);
    }

    #[Route('/network/{network_id}', name: 'app_transaction_table_index_network', methods: ['GET'])]
    public function indexNetwork(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository, int $network_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $network = $entityManager->getRepository(TransactionTable::class)->findByNetworkField($network_id);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_user.html.twig', [
            'transaction_tables' => $network,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все операции по месту в линии',
            'title' => 'transaction_tables_network',
            'type' => $this -> typeTransactions(),
        ]);
    }


    #[Route('/wallet/{wallet_id}', name: 'app_transaction_table_index_wallet', methods: ['GET'])]
    public function indexWallet(TransactionTableRepository $transactionTableRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository, int $wallet_id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 

        $wallet = $entityManager->getRepository(TransactionTable::class)->findByWalletField($wallet_id);
        //dd($add_wallet);
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/index_user.html.twig', [
            'transaction_tables' => $wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Все операции по месту номеру кошелька',
            'title' => 'transaction_tables_wallet',
            'type' => $this -> typeTransactions(),
        ]);
    }

    #[Route('/new', name: 'app_transaction_table_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TransactionTableRepository $transactionTableRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $transactionTable = new TransactionTable();
        $form = $this->createForm(TransactionTableType::class, $transactionTable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transactionTableRepository->add($transactionTable);
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('transaction_table/new.html.twig', [
            'transaction_table' => $transactionTable,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_transaction_table_show', methods: ['GET'])]
    public function show(TransactionTable $transactionTable,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager(); 
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            dd($fast_consultation->getName());
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->render('transaction_table/show.html.twig', [
            'transaction_table' => $transactionTable,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Просмотр операции',
            'title' => 'transaction_show',
        ]);
    }

    // #[Route('/{id}/edit', name: 'app_transaction_table_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, TransactionTable $transactionTable, TransactionTableRepository $transactionTableRepository): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
    //     $form = $this->createForm(TransactionTableType::class, $transactionTable);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $transactionTableRepository->add($transactionTable);
    //         return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('transaction_table/edit.html.twig', [
    //         'transaction_table' => $transactionTable,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_transaction_table_delete', methods: ['POST'])]
    // public function delete(Request $request, TransactionTable $transactionTable, TransactionTableRepository $transactionTableRepository): Response
    // {
    //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
    //     if ($this->isCsrfTokenValid('delete'.$transactionTable->getId(), $request->request->get('_token'))) {
    //         $transactionTableRepository->remove($transactionTable);
    //     }

    //     return $this->redirectToRoute('app_transaction_table_index', [], Response::HTTP_SEE_OTHER);
    // }

    private function typeTransactions(){
        return $type = [
            0 => '',
            1 => 'Директ',
            2 => 'SingleLine',
            3 => 'Перевод из линии на кошелек',
            4 => 'Вывод из системы',
            5 => 'Подарок пользователю',
            6 => 'Подарок от пользователя',
            7 => 'Новый пакет',
            8 => 'Повышение пакета',
            9 => 'Обмен CoMeta на USDT',
            10 => 'Обмен USDT на CoMeta',
            11 => 'Зачисление на кошелек подарочных CoMeta',
            12 => 'Списание с кошелька подаренных CoMeta',
            13 => 'Начисления за подарочный пакет',
            14 => 'Списание с кошелька USDT за покупку нового пакета',
            15 => 'Списание с кошелька Ditcoin за покупку нового пакета',
            16 => 'Списание с кошелька Etherium за покупку нового пакета',
            17 => 'Списание с кошелька USDT за повышение пакета',
            18 => 'Списание с кошелька Bitcoin за повышение пакета',
            19 => 'Списание с кошелька Etherium за повышение пакета',
            20 => 'Списание с кошелька CoMeta за покупку нового пакета',
            21 => 'Списание с кошелька CoMeta за повышение пакета',
            22 => 'Директ за повышение пакета',
            23 => 'SinglLine за повышение пакета',
            24 => 'Пополнение кошелька USDT',
            25 => 'Пополнение кошелька Bitcoin',
            26 => 'Пополнение кошелька Etherium',
            27 => 'Обмен CoMeta на USDT',
            28 => 'Обмен CoMeta на Bitcoin',
            29 => 'Обмен CoMeta на Etherium',
            30 => 'Обмен USDT на Bitcoin',
            31 => 'Обмен USDT на Etherium',
            32 => 'Обмен USDT на CoMeta',
        ];
    }
}
