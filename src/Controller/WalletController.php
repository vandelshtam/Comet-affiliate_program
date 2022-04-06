<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Form\WalletType;
use App\Entity\TokenRate;
use App\Form\WalletUsdtType;
use App\Entity\ReferralNetwork;
use App\Form\WalletExchangeType;
use App\Form\WalletExchangeUsdtType;
use App\Repository\WalletRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\WalletDepositFromSingllineType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/wallet')]
class WalletController extends AbstractController
{
    #[Route('/', name: 'app_wallet_index', methods: ['GET'])]
    public function index(WalletRepository $walletRepository,ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //if($this->denyAccessUnlessGranted('ROLE_ADMIN')){
            $wallet = $walletRepository->findAll();
        // }
        // else{
        //     $user_id = $this -> getUser() -> getId();
        //     //dd($user_id);
        //     $entityManager = $doctrine->getManager();
        //     $wallet = $walletRepository->findAll();
        //     //$wallet = $entityManager->getRepository(Wallet::class)->findOneBySomeField($user_id);
        //     //dd($wallet);
        // }
        
        return $this->render('wallet/index.html.twig', [
            'wallets' => $wallet,
        ]);
    }

    #[Route('/new', name: 'app_wallet_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WalletRepository $walletRepository): Response
    {
        $wallet = new Wallet();
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $walletRepository->add($wallet);
            return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/new.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }

    #[Route('/show', name: 'app_wallet_user', methods: ['GET'])]
    public function showUser(ManagerRegistry $doctrine): Response
    {
        $user_id = $this -> getUser() -> getId();
        //dd($user_id);
        $entityManager = $doctrine->getManager();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $wallet = $entityManager->getRepository(Wallet::class)->findOneBySomeField($user_id);
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
            'token_rate' => $token_rate,
        ]);
    }

    #[Route('/{id}', name: 'app_wallet_show', methods: ['GET'])]
    public function show(Wallet $wallet): Response
    {
        return $this->render('wallet/show.html.twig', [
            'wallet' => $wallet,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_wallet_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Wallet $wallet, WalletRepository $walletRepository): Response
    {
        $form = $this->createForm(WalletType::class, $wallet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $walletRepository->add($wallet);
            return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/edit.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/deposit', name: 'app_wallet_adddeposit', methods: ['GET', 'POST'])]
    public function deposit(Request $request, Wallet $wallet, WalletRepository $walletRepository,ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $form = $this->createForm(WalletUsdtType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            //$form_usdt = $form->get('usdt')->getData();
            $table_form = $wallet -> getUsdt();
            $wallet_new_deposit = $table_form + $table_usdt;
            //dd($wallet_new_deposit);

            $table_wallet->setUsdt($wallet_new_deposit);
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно пополнили кошелек.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/deposit_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/exchangecomet', name: 'app_wallet_exchange_comet', methods: ['GET', 'POST'])]
    public function exchange(Request $request, Wallet $wallet, WalletRepository $walletRepository,ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $table_bitcoin = $wallet -> getBitcoin();
        $table_etherium = $wallet -> getEtherium();
        $table_cometpoin = $wallet -> getCometpoin();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletExchangeType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            //$table_form = $wallet -> getCometpoin() / $token_rate;
            //dd($table_form);
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
                //dd($wallet_new_deposit);
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
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Etherium');
                return $this->redirectToRoute('app_wallet_exchange_comet', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $wallet_new_cometpoin = $table_cometpoin - ($wallet -> getCometpoin());
            //$table_form = $wallet -> getUsdt();
            //$wallet_new_deposit = $table_form + $table_usdt;
            //dd($wallet_new_cometpoin);

            $table_wallet->setCometpoin($wallet_new_cometpoin);
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно провели обмен монет.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_comet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/exchangeusdt', name: 'app_wallet_exchange_usdt', methods: ['GET', 'POST'])]
    public function exchangeUsdt(Request $request, Wallet $wallet, WalletRepository $walletRepository,ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $table_usdt = $wallet -> getUsdt();
        $table_bitcoin = $wallet -> getBitcoin();
        $table_etherium = $wallet -> getEtherium();
        $table_cometpoin = $wallet -> getCometpoin();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletExchangeUsdtType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            //$table_form = $wallet -> getCometpoin() / $token_rate;
            //dd($table_form);
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
                //dd($wallet_new_deposit);
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
                $this->addFlash(
                    'info',
                    'Извините, пока нет возможности конверитровать Cometpoin в Etherium');
                return $this->redirectToRoute('app_wallet_exchange_usdt', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            $wallet_new_cometpoin = $table_cometpoin - ($wallet -> getCometpoin());
            //$table_form = $wallet -> getUsdt();
            //$wallet_new_deposit = $table_form + $table_usdt;
            //dd($wallet_new_cometpoin);

            $table_wallet->setCometpoin($wallet_new_cometpoin);
            $entityManager->persist($table_wallet);
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно провели обмен монет.');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/exchange_usdt.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
        ]);
    }



    #[Route('/{id}/exchangecometwallet', name: 'app_wallet_exchange_wallet_comet', methods: ['GET', 'POST'])]
    public function exchangeCometWallet(Request $request, Wallet $wallet, WalletRepository $walletRepository,ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $table_wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $id]);
        $user_id = $wallet -> getUser() -> getId();
        $cuttent_wallet_coin = $table_wallet -> getCometpoin();//текущие средвтсва кометапоин на кошельке
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneByReferralField($user_id);
        $reward_wallet = $referral_network -> getRewardWallet();//начисления всети сингллайн доступные для перевода в кошелек
        $withdrawal_wallet = $referral_network -> getWithdrawalToWallet();//учет ввсех сумм с накоплением выведенных из  сингллайн на кошелек
        //dd($referral_network);

        $table_cometpoin = $wallet -> getCometpoin();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $form = $this->createForm(WalletDepositFromSingllineType::class, $wallet);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $table_form = $wallet -> getCometpoin();//сумма кометпоин введенная в форму
            if(($table_form / $token_rate) > $reward_wallet){
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
            $entityManager->flush();
            $this->addFlash(
                'info',
                'Вы успешно перевели монеты Cometpoin из сети на кошелек');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('wallet/deposit_reward_wallet.html.twig', [
            'wallet' => $wallet,
            'form' => $form,
            'reward_wallet' => $reward_wallet * $token_rate,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_wallet_delete', methods: ['POST'])]
    public function delete(Request $request, Wallet $wallet, WalletRepository $walletRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wallet->getId(), $request->request->get('_token'))) {
            $walletRepository->remove($wallet);
        }

        return $this->redirectToRoute('app_wallet_index', [], Response::HTTP_SEE_OTHER);
    }
}
