<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletType;
use App\Entity\TokenRate;
use App\Form\WalletUsdtType;
use App\Entity\SettingOptions;
use App\Entity\ReferralNetwork;
use App\Form\WalletBitcoinType;
use App\Entity\FastConsultation;
use App\Form\WalletEtheriumType;
use App\Form\WalletExchangeType;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Form\WalletExchangeUsdtType;
use App\Repository\WalletRepository;
use App\Repository\SavingMailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\WalletDepositFromSingllineType;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/wallet')]
class WalletController extends AbstractController
{
    #[Route('/admin', name: 'app_wallet_index_admin', methods: ['GET'])]
    public function index(Request $request, WalletRepository $walletRepository,EntityManagerInterface $entityManager,MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $wallet = $walletRepository->findAll();
        
        $wallets_array_usdt = [];
        foreach($wallet as $wall){
            $wallets_array_usdt[] = $wall -> getUsdt();
        }
        $wallet_summ_usdt = array_sum($wallets_array_usdt);

        $wallets_array_bitcoin = [];
        foreach($wallet as $wall){
            $wallets_array_bitcoin[] = $wall -> getBitcoin();
        }
        $wallet_summ_bitcoin = array_sum($wallets_array_bitcoin);

        $wallets_array_etherium = [];
        foreach($wallet as $wall){
            $wallets_array_etherium[] = $wall -> getEtherium();
        }
        $wallet_summ_etherium = array_sum($wallets_array_etherium);

        $wallets_array_cometpoin = [];
        foreach($wallet as $wall){
            $wallets_array_cometpoin[] = $wall -> getCometpoin();
        }
        $wallet_summ_cometpoin = array_sum($wallets_array_cometpoin);

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('wallet/index.html.twig', [
            'wallets' => $wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'wallet_summ_cometpoin' => $wallet_summ_cometpoin,
            'wallet_summ_usdt' => $wallet_summ_usdt,
            'wallet_summ_bitcoin' => $wallet_summ_bitcoin,
            'wallet_summ_etherium' => $wallet_summ_etherium,
            'controller_name' => 'Все кошельки',
            'title' => 'all wallet',
        ]);
    }

    #[Route('/new/admin', name: 'app_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $wallet = new Wallet();
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wallet->setUpdatedAt(new \DateTimeImmutable());
            $walletRepository->add($wallet);
            return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/new.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Новый кошелек',
            'title' => 'new wallet',
        ]);
    }

    #[Route('/show', name: 'app_wallet_user', methods: ['GET'])]
    public function showUser(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $user_id = $this -> getUser() -> getId();
        
        $wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['user_id' => $user_id]);
        
        if($wallet == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку или вам нужно пройти полную регистрацию');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'token_rate' => $token_rate,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Мой кошелек',
            'title' => 'my wallet',
        ]);
    }

    #[Route('/{id}/admin', name: 'app_wallet_show', methods: ['GET'])]
    public function show(Request $request, Wallet $wallet,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $user_id = $this -> getUser() -> getId();
        
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'token_rate' => $token_rate,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Кошелек',
            'title' => 'wallet',
        ]);
    }

    

    #[Route('/{id}/edit/admin', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $entityManager = $doctrine->getManager();
        $user_id = $this -> getUser() -> getId();
        
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wallet->setUpdatedAt(new \DateTimeImmutable());
            $walletRepository->add($wallet);
            return $this->redirectToRoute('app_wallet_index_admin', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Редактирование кошелька админ',
            'title' => 'admin edit wallet',
        ]);
    }

    #[Route('/{id}/deposit', name: 'app_wallet_adddeposit', methods: ['GET', 'POST'])]
    public function deposit(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, SavingMailRepository $savingMailRepository,int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $user_id = $this -> getUser() -> getId();
        $wallet_user = $this -> getUser() ->getWallet();
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'You do not have permission to access the wallet.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $form = $this->createForm(WalletUsdtType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            
            $table_form = $wallet -> getUsdt();
            $wallet_new_deposit = $table_form + $table_usdt;

            $table_wallet->setUsdt($wallet_new_deposit);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'wallet.wallet_adddeposit');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/deposit_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Пополнение кошелька',
            'title' => 'addwallet',
        ]);
    }



    #[Route('/{id}/deposit/bitcoin', name: 'app_wallet_adddeposit_bitcoin', methods: ['GET', 'POST'])]
    public function depositBitcoin(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $user_id = $this -> getUser() -> getId();
        $wallet_user = $this -> getUser() ->getWallet();
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_bitcoin = $wallet -> getBitcoin();
        $form = $this->createForm(WalletBitcoinType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $table_form = $wallet -> getBitcoin();
            $wallet_new_deposit = $table_bitcoin + $table_form;

            $table_wallet->setBitcoin($wallet_new_deposit);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно пополнили кошелек.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/deposit_bitcoin.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Пополнение кошелька',
            'title' => 'addwallet',
        ]);
    }

    #[Route('/{id}/deposit/etherium', name: 'app_wallet_adddeposit_etherium', methods: ['GET', 'POST'])]
    public function depositEtherium(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $user_id = $this -> getUser() -> getId();
        $wallet_user = $this -> getUser() ->getWallet();
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_etherium = $wallet -> getEtherium();
        $form = $this->createForm(WalletEtheriumType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $table_form = $wallet -> getEtherium();
            $wallet_new_deposit = $table_etherium + $table_form;

            $table_wallet->setEtherium($wallet_new_deposit);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно пополнили кошелек.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/deposit_etherium.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Пополнение кошелька',
            'title' => 'addwallet',
        ]);
    }


    #[Route('/{id}/exchangecomet', name: 'app_wallet_exchange_comet', methods: ['GET', 'POST'])]
    public function exchange(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $user_id = $this -> getUser() -> getId();
        
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        //dd($wallet_user->getUser()->getId());
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $table_bitcoin = $wallet -> getBitcoin();
        $table_etherium = $wallet -> getEtherium();
        $table_cometpoin = $wallet -> getCometpoin();
        
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletExchangeType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->get('usdt')->getData());
            if($wallet -> getCometpoin()  > $table_cometpoin){
                $this->addFlash(
                    'warning',
                    'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_cometpoin.' Cometpoin');
                return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            if($form_coin = $form->get('usdt')->getData() == 1){
                $table_form_input = $wallet -> getCometpoin();
                $table_form = $table_form_input / $token_rate;
                $wallet_new_deposit = $table_form + $table_usdt;
                $table_wallet->setUsdt($wallet_new_deposit);
            }
            elseif($form_coin = $form->get('usdt')->getData() == 3){
                $table_form_input = $wallet -> getCometpoin();
                $table_form = $table_form_input / (40000 * $token_rate);
                $wallet_new_deposit = $table_form + $table_bitcoin;
                $table_wallet->setBitcoin($wallet_new_deposit); 
                $table_wallet->setUsdt($table_usdt); 
            }
            elseif($form_coin = $form->get('usdt')->getData() == 2){
                $table_form_input = $wallet -> getCometpoin();
                $table_form = $table_form_input / (4000 * $token_rate);
                //dd($table_form);
                $wallet_new_deposit = $table_form + $table_etherium;
                $table_wallet->setEtherium($wallet_new_deposit);
                $table_wallet->setUsdt($table_usdt);    
            }

            $wallet_new_cometpoin = $table_cometpoin - ($wallet -> getCometpoin());
            $table_wallet->setCometpoin($wallet_new_cometpoin);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно провели обмен монет.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_comet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Конвертация монет CoMetaCoin',
            'title' => 'converter wallet CoMetaCoin',
        ]);
    }


    #[Route('/{id}/exchangeusdt', name: 'app_wallet_exchange_usdt', methods: ['GET', 'POST'])]
    public function exchangeUsdt(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();

        $user_id = $this -> getUser() -> getId();
        
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }


        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $table_bitcoin = $wallet -> getBitcoin();
        $table_etherium = $wallet -> getEtherium();
        $table_cometpoin = $wallet -> getCometpoin();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletExchangeUsdtType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form_coin = $form->get('cometpoin')->getData());
            if($form->get('usdt')->getData()  > $table_usdt){
                $this->addFlash(
                    'warning',
                    'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_usdt.' USDT');
                return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $new_table_usdt = $table_usdt - $form->get('usdt')->getData();

            if($form_coin = $form->get('cometpoin')->getData() == 1){  
                $table_form_token = $form->get('usdt')->getData() * $token_rate;
                $wallet_new_deposit = $table_form_token + $table_cometpoin;
                $table_wallet->setCometpoin($wallet_new_deposit); 
                //dd($wallet_new_deposit); 
            }
            elseif($form_coin = $form->get('cometpoin')->getData() == 3){
                $table_form = $form->get('usdt')->getData() / 40000;
                $wallet_new_deposit = $table_form + $table_bitcoin;
                $table_wallet->setBitcoin($wallet_new_deposit);    
            }
            elseif($form_coin = $form->get('cometpoin')->getData() == 2){   
                $form_token = $form->get('usdt')->getData() / 4000;
                $wallet_new_deposit = $form_token + $table_etherium;
                $table_wallet->setEtherium($wallet_new_deposit);    
            }
            
            $table_wallet->setUSDT($new_table_usdt);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно провели обмен монет.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Конвертация USDT',
            'title' => 'USDT converter',
        ]);
    }


    #[Route('/{id}/exchangecometwallet/select', name: 'app_wallet_exchange_wallet_comet_select', methods: ['GET', 'POST'])]
    public function exchangeCometWalletSelect(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        
        $user_id = $wallet_user -> getUser() -> getId();
        //dd($token_rate);
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        if($user_id != $this -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        
        $user_client_code = $this -> getUser() -> getPesonalCode();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField($user_id);
        if(count($referral_network) == 1){
            $network_id = $referral_network -> getId();
            return $this->redirectToRoute('app_wallet_exchange_wallet_comet', ['network' => $network_id], Response::HTTP_SEE_OTHER);
        }
        elseif(count($referral_network) > 1){
            return $this->redirectToRoute('app_wallet_index_user_select', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
    }



    #[Route('/{id}/select', name: 'app_wallet_index_user_select', methods: ['GET', 'POST'])]
    public function indexUser(ReferralNetworkRepository $referralNetworkRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $entityManager = $doctrine->getManager();
        $referral_networks = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField($this->getUser()->getId());
        $user_id = $referral_networks[0] -> getUserId();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//курс токена
        //dd($referral_networks);
        if($referral_networks == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        if($user_id != $this -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $array_referral_networks = [];
        $array_referral_networks_no = [];
        foreach($referral_networks as $network){
            if($network-> getWithdrawalToWallet() < ($network->getReward() * 70)/100){
                $array_referral_networks[] = $network;
            }
            else{
                $array_referral_networks_no[] = $network;
            }    
        }


        $array_summ_cash = [];
        foreach($referral_networks as $network){
            $array_summ_cash[] = $network -> getCash();
        }
        $summ_cash = array_sum($array_summ_cash);

        $array_summ_direct = [];
        foreach($referral_networks as $network){
            $array_summ_direct[] = $network -> getDirect();
        }
        $summ_direct = array_sum($array_summ_direct);

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_referral_network_show', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->renderForm('wallet/index_my_balances_select.html.twig', [
            'referral_networks' => $array_referral_networks,
            'referral_networks_no' => $array_referral_networks_no,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Балансы моих пакетов',
            'title' => 'my balances',
            'token_rate' => $token_rate,
            'summ_direct' => $summ_direct,
            'summ_cash' => $summ_cash,
        ]);
    }

    #[Route('/exchangecometwallet/{network}', name: 'app_wallet_exchange_wallet_comet', methods: ['GET', 'POST'])]
    public function exchangeCometWallet(Request $request, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $network): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $doctrine->getManager();
        
        $user_id = $this -> getUser() -> getId();
        $id = $this -> getUser() -> getWallet() -> getId();
        $wallet = $this -> getUser() -> getWallet();
        //dd($id);
        $wallet_user = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        
        if($wallet_user == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа к кошельку.');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        if($user_id != $wallet_user -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }


        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        //$user_id = $wallet -> getUser() -> getId();
        $cuttent_wallet_coin = $table_wallet -> getCometpoin();//текущие средвтсва кометапоин на кошельке
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['id' => $network]);
        $reward_all = $referral_network -> getReward();//все начисления в сети сингллайн за все время
        $reward_wallet = $referral_network -> getRewardWallet();//начисления в сети сингллайн доступные для перевода в кошелек
        $withdrawal_wallet = $referral_network -> getWithdrawalToWallet();//учет ввсех сумм с накоплением выведенных из  сингллайн на кошелек

        //рачет - лимит вывода из сети (линии) на кошелек
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $limit_wallet_from_line = $setting_opyions -> getLimitWalletFromLine();//коеф лимита для  вывода из сети на кошелек 70%
        //dd($referral_network);
        $fast_start = $setting_opyions -> getFastStart();
        $available_amount = ($reward_all * $limit_wallet_from_line) / 100; //общая сумма доступная для вывода - контрольная с которой сравниваем выводимые средства
        $available_balance = $available_amount - $withdrawal_wallet;//доступный остаток для вывода в момент запроса

        if($available_balance == 0 ){
            $this->addFlash(
                'warning',
                'Извините, операция не может быть выполнена! У вас недостаточно средств или  вы вывели все доступные средства, чтобы продолжить зарабатывать в сети вам нужно повысить пакет и пригласить новых партнеров');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }

        $table_cometpoin = $wallet -> getCometpoin();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletDepositFromSingllineType::class, $wallet);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $table_form = $wallet -> getCometpoin();//сумма кометпоин введенная в форму
            if(($table_form / $token_rate) > $available_balance){
                $this->addFlash(
                    'warning',
                    'Сумма введенная для перевода на кошелек превышает остаток начислений в сети доступный для перевода, введите другую сумму не превышающую' .$reward_wallet.' Cometpoin');
                return $this->redirectToRoute('app_wallet_exchange_wallet_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $table_form = $wallet -> getCometpoin();
            $reward_new_singl = $reward_wallet - ($table_form / $token_rate);//остаток кометпоин доступных для перевода на кошелек
            $wallet_new_cometpoin = $cuttent_wallet_coin + $table_form;//новая сумма баланса на кошельке
            $withdrawal_to_wallet = $withdrawal_wallet + ($table_form / $token_rate);//новый баланс учеты всех  выведенных кометпоинтов из сети на кошелек
            $table_wallet->setCometpoin($wallet_new_cometpoin);
            $referral_network -> setRewardWallet($reward_new_singl);
            $referral_network -> setWithdrawalToWallet($withdrawal_to_wallet);
            //dd($withdrawal_to_wallet);
            $entityManager->persist($table_wallet);
            $table_wallet->setUpdatedAt(new \DateTime());
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно перевели монеты Cometpoin из сети на кошелек');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('wallet/deposit_reward_wallet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'reward_wallet' => $reward_wallet * $token_rate,
            'reward_all' => $reward_all * $token_rate,
            'fast_consultation_form' => $fast_consultation_form,
            'available_balance' => $available_balance * $token_rate,
            'controller_name' => 'Обмен токенов',
            'title' => 'exchange token',
            'withdrawal_wallet' => $withdrawal_wallet * $token_rate,
        ]);
    }

    #[Route('/{id}/delete/admin', name: 'app_wallet_delete', methods: ['POST'])]
    public function delete(Request $request, Wallet $wallet, WalletRepository $walletRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$wallet->getId(), $request->request->get('_token'))) {
            $walletRepository->remove($wallet);
        }

        return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
    }
}
