<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletType;
use App\Entity\TokenRate;
use App\Form\WalletUsdtType;
use App\Entity\SettingOptions;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\WalletExchangeType;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Form\WalletExchangeUsdtType;
use App\Repository\WalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\WalletDepositFromSingllineType;
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
    public function index(Request $request, WalletRepository $walletRepository,MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('wallet/index.html.twig', [
            'wallets' => $wallet,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'wallet_summ_cometpoin' => $wallet_summ_cometpoin,
            'wallet_summ_usdt' => $wallet_summ_usdt,
            'wallet_summ_bitcoin' => $wallet_summ_bitcoin,
            'wallet_summ_etherium' => $wallet_summ_etherium,
        ]);
    }

    #[Route('/new/admin', name: 'app_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/new.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/show', name: 'app_wallet_user', methods: ['GET'])]
    public function showUser(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'token_rate' => $token_rate,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/{id}/admin', name: 'app_wallet_show', methods: ['GET'])]
    public function show(Request $request, Wallet $wallet,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'token_rate' => $token_rate,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    

    #[Route('/{id}/edit/admin', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}/deposit', name: 'app_wallet_adddeposit', methods: ['GET', 'POST'])]
    public function deposit(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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
                'info',
                'Вы успешно пополнили кошелек.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('wallet/deposit_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }


    #[Route('/{id}/exchangecomet', name: 'app_wallet_exchange_comet', methods: ['GET', 'POST'])]
    public function exchange(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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

        if($user_id != $wallet_user -> getUserId){
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
            
            if($form_coin = $form->get('usdt')->getData() == 1){
                $table_form_input = $wallet -> getCometpoin();
                if($table_form_input  > $table_cometpoin){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_cometpoin.' Cometpoin');
                    return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input / $token_rate;
                $wallet_new_deposit = $table_form + $table_usdt;
                $table_wallet->setUsdt($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
            }
            elseif($form_coin = $form->get('usdt')->getData() == 2){
                $table_form_input = $wallet -> getCometpoin();
                if($table_form_input  > $table_cometpoin){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_cometpoin.' Cometpoin');
                    return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input / 50;
                $wallet_new_deposit = $table_form + $table_bitcoin;
                $table_wallet->setBitcoin($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Bitcoin');
                return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            elseif($form_coin = $form->get('usdt')->getData() == 3){
                $table_form_input = $wallet -> getCometpoin();
                if($table_form_input  > $table_cometpoin){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_cometpoin.' Cometpoin');
                    return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input / 100;
                $wallet_new_deposit = $table_form + $table_etherium;
                $table_wallet->setEtherium($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Etherium');
                return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $wallet_new_cometpoin = $table_cometpoin - ($wallet -> getCometpoin());

            $table_wallet->setCometpoin($wallet_new_cometpoin);
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_comet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }


    #[Route('/{id}/exchangeusdt', name: 'app_wallet_exchange_usdt', methods: ['GET', 'POST'])]
    public function exchangeUsdt(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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
            if($form_coin = $form->get('cometpoin')->getData() == 1){
                $table_form_input = $wallet -> getUsdt();
                if($table_form_input  > $table_usdt){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_usdt.' USDT');
                    return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input * $token_rate;
                $wallet_new_deposit = $table_form + $table_cometpoin;
                $table_wallet->setCometpoin($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
            }
            elseif($form_coin = $form->get('usdt')->getData() == 2){
                $table_form_input = $wallet -> getUsdt();
                if($table_form_input  > $table_usdt){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_usdt.' USDT');
                    return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input / 50;
                $wallet_new_deposit = $table_form + $table_bitcoin;
                $table_wallet->setBitcoin($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Bitcoin');
                return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            elseif($form_coin = $form->get('usdt')->getData() == 3){
                $table_form_input = $wallet -> getUsdt();
                if($table_form_input  > $table_usdt){
                    $this->addFlash(
                        'warning',
                        'Сумма введенная для конвертации превышает  доступную на кошельке, введите другую сумму не превышающую : ' .$table_usdt.' USDT');
                    return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                $table_form = $table_form_input / 100;
                $wallet_new_deposit = $table_form + $table_etherium;
                $table_wallet->setEtherium($wallet_new_deposit);
                $table_wallet->setUpdatedAt(new \DateTime());
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Etherium');
                return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $wallet_new_cometpoin = $table_cometpoin - ($wallet -> getCometpoin());
            
            $table_wallet->setCometpoin($wallet_new_cometpoin);
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }



    #[Route('/{id}/exchangecometwallet', name: 'app_wallet_exchange_wallet_comet', methods: ['GET', 'POST'])]
    public function exchangeCometWallet(Request $request, Wallet $wallet, WalletRepository $walletRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
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

        if($user_id != $wallet_user -> getUserId){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }


        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $user_id = $wallet -> getUser() -> getId();
        $cuttent_wallet_coin = $table_wallet -> getCometpoin();//текущие средвтсва кометапоин на кошельке
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneByReferralField($user_id);
        $reward_all = $referral_network -> getRewardWallet();//все начисления всети сингллайн за все время
        $reward_wallet = $referral_network -> getRewardWallet();//начисления всети сингллайн доступные для перевода в кошелек
        $withdrawal_wallet = $referral_network -> getWithdrawalToWallet();//учет ввсех сумм с накоплением выведенных из  сингллайн на кошелек

        //рачет - лимит вывода из сети (линии) на кошелек
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $limit_wallet_from_line = $setting_opyions -> getLimitWalletFromLine();//коеф лимита для  вывода из сети на кошелек
        $fast_start = $setting_opyions -> getFastStart();
        $available_amount = ($reward_all * $limit_wallet_from_line) / 100; //общая сумма доступная для вывода - контрольная с которой сравниваем выводимые средства
        $available_balance = $available_amount - $withdrawal_wallet;//доступный остаток для вывода в момент запроса

        if($available_balance == 0 ){
            $this->addFlash(
                'warning',
                'Извините, операция не может быть выполнена! Вы вывели все доступные средства, чтобы продолжить зарабатывать в сети вам нужно повысить пакет и пригласить новых партнеров');
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
            $referral_network -> getWithdrawalToWallet($withdrawal_to_wallet);
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
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('wallet/deposit_reward_wallet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'reward_wallet' => $reward_wallet * $token_rate,
            'fast_consultation_form' => $fast_consultation_form,
            'available_balance' => $available_balance,
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
