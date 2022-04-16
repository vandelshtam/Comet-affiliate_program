<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\Wallet;
use App\Form\PakegeType;
use App\Entity\TokenRate;
use App\Entity\TablePakage;
use App\Entity\PersonalData;
use App\Form\BoostPakageType;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\PakegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use App\Entity\ListReferralNetworks;
use App\Entity\SettingOptions;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pakege')]
class PakegeController extends AbstractController
{
    #[Route('/admin', name: 'app_pakege_index_admin', methods: ['GET'])]
    public function index(PakegeRepository $pakegeRepository, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $pakages = $pakegeRepository->findAll();
        $pakages_array_price = [0];
        
        foreach($pakages as $pakage){
            $pakages_array_price[] = $pakage -> getPrice();
        }
        $pakage_summ_usdt = array_sum($pakages_array_price);

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pakege/index.html.twig', [
            'pakeges' => $pakegeRepository->findAll(),
            'count' => count($pakegeRepository->findAll()),
            'controller_name' => 'Список всех приобретенных пакетов в сети',
            'title' => 'Pakages',
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'pakage_summ_usdt' => $pakage_summ_usdt,
        ]);
    }

    #[Route('/new', name: 'app_pakege_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PakegeRepository $pakegeRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this -> getUser();
        $user_id = $user -> getId();
        $wallet_id = $user -> getWallet() -> getId();
        
        $pakage_table = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id' => $user_id]);
        if($pakage_table == true){    
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
        }
        
        //проверка наличия кошелька для покупки нового пакета
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);

        if( $wallet_id == NULL){
            $this->addFlash(
                'warning',
                'Вы заполнили не все данные, у вас пока нет кошелька, пройдите полную регитрацию');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        $wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $wallet_id]);
        $pakege = new Pakege();
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        
        $user = $this -> getUser();
        $user_referral_link = $user -> getReferralLink();
        if($user_referral_link == NULL){
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
        }
        
        $entityManager = $doctrine->getManager();
        
        $unique_code1 = $this->random_string(10);
        $unique_code2 = $this->random_string(10);
        $unique_code = $unique_code1.$unique_code2;
        $collection = new ArrayCollection();
        $collection -> add($unique_code);

        if ($form->isSubmitted() && $form->isValid()) {
            $form_referral_link = $form->get('referral_link')->getData();
            
            $form_pakage_name = $form->get('name')->getData();
            $form_referral_select = $request->get('select');
            
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_pakage_name]); 
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $wallet_cometpoin = $wallet -> getCometpoin();
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;
            $pakage_user_price = $pakage_user -> getPricePakage();
            if($form_referral_select == 1){

                if(($wallet -> getUsdt()) < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            elseif($form_referral_select == 2){
                if($wallet_cometcoin_rate < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            
            if($form_referral_link != NULL){
                if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $form_referral_link]) == false ){
                
                $this->addFlash(
                    'danger',
                    'Вы ошиблись при введении ссылки или ввели устаревшую ссылку, пожалуйста попробуйте еще раз');
                return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            elseif($form_referral_link == NULL){
                $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
            }
            
            $pakege -> setCreatedAt(new \DateTime());    
            $pakegeRepository->add($pakege);
            $unique = $form->get('unique_code')->getData();

            $pakage_comet_id = $this -> newChoice ($request,$pakegeRepository,$doctrine,$unique,$wallet,$form_referral_select,$form_pakage_name,$pakage_user_price,$token_rate,$user_table);
            
            $user_table -> setPakageId($pakage_comet_id);
            $entityManager->flush();
            $mailerController->sendEmail($mailer);
            $this->addFlash(
                'success',
                'Вы успешно приобрели новый пакет, на электронную почту отправлено подтверждение операции');
            $this->addFlash(
                'info',
                'Чтобы пакет начал работать вы должны активировать пакет!'); 
                
            return $this->redirectToRoute('app_pakege_show', ['id' => $pakage_comet_id], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('pakege/new.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $collection[0],
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_show', methods: ['GET'])]
    public function show(Pakege $pakege,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $user_id = $user -> getId();
        $pakage_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        if($user_id != $pakage_user -> getUserId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
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

        return $this->render('pakege/show.html.twig', [
            'pakege' => $pakege,
            'fast_consultation_form' => $fast_consultation_form -> createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pakege_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
    {
        $user = $this->getUser();
        $wallet = $user -> getWallet();
        $user_id = $user -> getId();
        //получение переменных занчений настройки линии
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $network_code = $pakage_comet -> getReferralNetworksId();
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $update_day = $setting_opyions -> getUpdateDay();
        $fast_start = $setting_opyions -> getFastStart();
        $payments_direct = $setting_opyions -> getPaymentsDirect();
        $payments_direct_fast = $setting_opyions -> getPaymentsDirectFast();
        $payments_singleline = $setting_opyions -> getPaymentsSingleline();
       // dd($update_day);
        if($user_id != $pakage_comet -> getUserId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        //проверка срока активации пакета
        //article.created_at|date('m-d')
        if($pakage_comet -> getUpdatedAt() != NULL){
            $datetime = $pakage_comet -> getCreatedAt();
            $timestamp = $datetime->getTimestamp();
            date_modify($datetime, $update_day.'day');
            if(time() > $timestamp){
                $this->addFlash(
                    'warning',
                    'Вы не можете повысить уровень пакета, срок предусмотренный для повышения пакета истек');
                return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
            }    
        }
        else{
            $datetime = $pakage_comet -> getCreatedAt();
            $timestamp = $datetime->getTimestamp();
            date_modify($datetime, $update_day.'day');
            if(time() > $timestamp){
                $this->addFlash(
                    'warning',
                    'Вы не можете повысить уровень пакета, срок предусмотренный для повышения пакета истек');
                return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
            }    
        }

        //проверка срока быстрого старта и получения двойного бонуса Директ 20%
        if($pakage_comet -> getUpdatedAt() != NULL){
            $datetime = $pakage_comet -> getUpdatedAt();
            $timestamp = $datetime->getTimestamp();
            date_modify($datetime, $fast_start.'day');
            if(time() > $timestamp){
                $k_direct = $payments_direct_fast;
            }
            else{
                $k_direct = $payments_direct;
            }    
        }
        else{
            $datetime = $pakage_comet -> getCreatedAt();
            $timestamp = $datetime->getTimestamp();
            date_modify($datetime, $fast_start.'day');
            //dd(time() - $this->timestamp = $datetime->getTimestamp());
            if(time() > $timestamp){
                $k_direct = $payments_direct_fast;
            }
            else{
                $k_direct = $payments_direct;
            }      
        }


        $form = $this->createForm(BoostPakageType::class, $pakege);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        
        if($pakage_comet-> getname() == 'Starter'){
            $choise  = [
                'Basic' => 'Basic',
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($pakage_comet-> getname() == 'Basic'){
            $choise  = [
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($pakage_comet-> getname() == 'Networker'){
            $choise  = [
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($pakage_comet-> getname() == 'Business'){
            $choise  = [
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($pakage_comet-> getname() == 'Trader'){
            $choise  = [
                'VIP' => 'VIP',
            ]; 
        }
        elseif($pakage_comet-> getname() == 'VIP'){
            $this->addFlash(
                'warning',
                'У вас самый высокий уровень пакета, вы не можете повысить пакет');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        $unique = $pakage_comet -> getUniqueCode();
        $user_referral_link = $pakage_comet -> getReferralLink();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $form_pakage = $request->get('pakage');//назание пакета из формы
            //dd($form_pakage);
            $form_referral_select = $request->get('select');//название валюты оплаты из формы
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//курс токена
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_pakage]); //объект всех пакетов
            $wallet_cometpoin = $wallet -> getCometpoin();//получение суммы токенов на кошельке
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;//перевеод токенов в юсдт по курсу токена
            $pakage_user_price = $pakage_user -> getPricePakage();//стоимость выбранного пакета 
            $pakage_current_price = $pakage_comet-> getPrice();//стоимость текущего пакета
            $pakage_cost_difference = $pakage_user_price - $pakage_current_price;//разниуа стоимости пакетов
            $token = $token_rate * $pakage_user_price;
            if($form_referral_select == 1){
                //юсдт
                if(($wallet -> getUsdt()) < $pakage_cost_difference ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                else{
                    $new_balanse_wallet = $wallet -> getUsdt() - $pakage_cost_difference;//новый баланс кошелька
                    $wallet -> setUsdt($new_balanse_wallet);
                    $wallet -> setUpdatedAt(new \DateTimeImmutable());
                    $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['pakege_id' => $id]);//объект владельца пакета
                    $referral_link_refovod = $referral_network_user -> getMyTeam();
                    //dd($referral_network_user);
                    //получаем объект Рефовода и получаем текщие значения начислений бонусов
                    $user_refovod = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link_refovod]);
                   // dd($user_refovod );
                    $user_refovod_curren_cash = $user_refovod -> getCash(); 
                    $user_refovod_curren_reward = $user_refovod -> getReward();
                    $user_refovod_curren_direct = $user_refovod -> getDirect();
                    $user_refovod_direct_bonus = ($pakage_cost_difference * $k_direct) / 100;
                    $user_refovod_cash_bonus = ($pakage_cost_difference * $payments_singleline) / 100;
                    $user_refovod_reward_bonus = $user_refovod_cash_bonus + $user_refovod_direct_bonus;
                    $user_refovod_direct_bonus_new = $user_refovod_curren_direct + $user_refovod_direct_bonus;
                    $user_refovod_cash_bonus_new = $user_refovod_curren_cash + $user_refovod_cash_bonus;
                    $user_refovod_reward_bonus_new = $user_refovod_curren_reward + $user_refovod_cash_bonus + $user_refovod_reward_bonus;
                    //получаем значения владельца пакета с текщим балансом и значениями отчисления в сеть на момент предыдущей активации пакета
                    $current_balance = $referral_network_user -> getBalance();//текущий баланс стоимости пакета пользователя в линии
                    $current_balance_direct = $referral_network_user -> getPaymentsNetwork();//текущий значение отчислений в сеть на момент последней активации пакета по программе Директ
                    $current_balance_cash = $referral_network_user -> getPaymentsCash();//текущий значение отчислений в сеть на момент последней активации пакета по программе CashBack
                    $balace_line_new = $current_balance + $pakage_cost_difference;//новый баланс стоимости пакета участника в линии
                    $new_balance_direct = $current_balance_direct + $user_refovod_direct_bonus;//обновленная сумма отчисления в линию по программе Директ на момент активации пакета
                    $new_balance_cash = $current_balance_cash + $user_refovod_cash_bonus;//обновленная сумма отчисления в линию по программе CashBack на момент активации пакета
                    $referral_network_user -> setBalance($balace_line_new);//запись нового баланса владельуц пакета
                    $referral_network_user -> setPakage($pakage_user_price);//запись нового значения стоимости нового пакета
                    $referral_network_user->setUpdatedAt(new \DateTime());
                    //запсиь новых начислений Рефоводу
                    $user_refovod -> setCash($user_refovod_cash_bonus_new); 
                    $user_refovod -> setReward($user_refovod_reward_bonus_new);
                    $user_refovod -> setDirect($user_refovod_direct_bonus_new);
                    $user_refovod->setUpdatedAt(new \DateTime());
                    //запись обновленных значений отчислений в сеть 
                    $referral_network_user -> setPaymentsCash($new_balance_cash);//запись оьновленный значений отчислений в сеть по программе КешБек
                    $referral_network_user -> setPaymentsNetwork($new_balance_direct);//запись оьновленный значений отчислений в сеть по программе Директ
                }
            }
            elseif($form_referral_select == 2){
                //внутренний токен
                if($wallet_cometcoin_rate < $pakage_cost_difference){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                else{
                    $new_balanse_wallet = $wallet_cometcoin_rate - $pakage_cost_difference;//новый баланс кошелька
                    $wallet -> setCometpoin($new_balanse_wallet);
                    $wallet -> setUpdatedAt(new \DateTimeImmutable());
                    $referral_network_user = $entityManager -> getRepository(ReferralNetwork::class)->findOneBy(['pakege_id' => $id]);//объект владельца пакета
                    $referral_link_refovod = $referral_network_user -> getMyTeam();
                    //dd($referral_network_user);
                    //получаем объект Рефовода и получаем текщие значения начислений бонусов
                    $user_refovod = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link_refovod]);
                    $user_refovod_curren_cash = $user_refovod -> getCash(); 
                    $user_refovod_curren_reward = $user_refovod -> getReward();
                    $user_refovod_curren_direct = $user_refovod -> getDirect();
                    $user_refovod_direct_bonus = ($pakage_cost_difference * $k_direct) / 100;
                    $user_refovod_cash_bonus = ($pakage_cost_difference * $payments_singleline) / 100;
                    $user_refovod_reward_bonus = $user_refovod_cash_bonus + $user_refovod_direct_bonus;
                    $user_refovod_direct_bonus_new = $user_refovod_curren_direct + $user_refovod_direct_bonus;
                    $user_refovod_cash_bonus_new = $user_refovod_curren_cash + $user_refovod_cash_bonus;
                    $user_refovod_reward_bonus_new = $user_refovod_curren_reward + $user_refovod_cash_bonus + $user_refovod_reward_bonus;
                    //получаем значения владельца пакета с текщим балансом и значениями отчисления в сеть на момент предыдущей активации пакета
                    $current_balance = $referral_network_user -> getBalance();//текущий баланс стоимости пакета пользователя в линии
                    $current_balance_direct = $referral_network_user -> getPaymentsNetwork();//текущий значение отчислений в сеть на момент последней активации пакета по программе Директ
                    $current_balance_cash = $referral_network_user -> getPaymentsCash();//текущий значение отчислений в сеть на момент последней активации пакета по программе CashBack
                    $balace_line_new = $current_balance + $pakage_cost_difference;//новый баланс стоимости пакета участника в линии
                    $new_balance_direct = $current_balance_direct + $user_refovod_direct_bonus;//обновленная сумма отчисления в линию по программе Директ на момент активации пакета
                    $new_balance_cash = $current_balance_cash + $user_refovod_cash_bonus;//обновленная сумма отчисления в линию по программе CashBack на момент активации пакета
                    $referral_network_user -> setBalance($balace_line_new);//запись нового баланса владельуц пакета
                    $referral_network_user -> setPakage($pakage_user_price);//запись нового значения стоимости нового пакета
                    $referral_network_user->setUpdatedAt(new \DateTime());
                    //запсиь новых начислений Рефоводу
                    $user_refovod -> setCash($user_refovod_cash_bonus_new); 
                    $user_refovod -> setReward($user_refovod_direct_bonus_new);
                    $user_refovod -> setDirect($user_refovod_reward_bonus_new);
                    $user_refovod->setUpdatedAt(new \DateTime());
                    //запись обновленных значений отчислений в сеть 
                    $referral_network_user -> setPaymentsCash($new_balance_cash);//запись оьновленный значений отчислений в сеть по программе КешБек
                    $referral_network_user -> setPaymentsNetwork($new_balance_direct);//запись оьновленный значений отчислений в сеть по программе Директ
                }
            }

            //==================получаем и записываем  все начисления и погашеня сети ==============================================
            $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['network_code' => $network_code]);//обект родительской сети
            //получение текущих значений 
            //$listReferralNetwork -> setProfitNetwork($curren_network_summ);//общая сумма погашения пакетов на момент активации последнего пакета
            $listReferralNetwork_current_direct  = $listReferralNetwork -> getPaymentsDirect();//общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
            $listReferralNetwork_current_cash  = $listReferralNetwork -> getPaymentsCash();//общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
            $listReferralNetwork_current_balance  = $listReferralNetwork -> getCurrentBalance();//текущая общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета

            //новые значения
            $listReferralNetwork_new_direct = $user_refovod_direct_bonus + $listReferralNetwork_current_direct;
            $listReferralNetwork_new_cash = $user_refovod_cash_bonus + $listReferralNetwork_current_cash;
            $listReferralNetwork_new_balance = $pakage_cost_difference + $listReferralNetwork_current_balance;
            //dd($listReferralNetwork_new_direct);
            //запись данных начислений во всей сети в родительский объект сети
            //$listReferralNetwork -> setProfitNetwork($curren_network_summ);//обновленная общая сумма погашения пакетов на момент активации последнего пакета
            $listReferralNetwork -> setPaymentsDirect($listReferralNetwork_new_direct);// обновленная общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
            $listReferralNetwork -> setPaymentsCash($listReferralNetwork_new_cash);//обновленная общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
            $listReferralNetwork -> setCurrentBalance($listReferralNetwork_new_balance);//новая  общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета
            $listReferralNetwork -> setUpdatedAt(new \DateTimeImmutable());
            //=========== =========================================================== ==============================================

            $pakage_comet -> setPrice($pakage_user_price);
            $pakage_comet -> setName($form_pakage);
            $pakage_comet -> setToken($token);
            $pakage_comet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                'Вы успешно приобрели новый пакет, на электронную почту отправлено подтверждение операции');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
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

        return $this->renderForm('pakege/edit_boost.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $unique,
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
            'choise' => $choise,
        ]);
    }


    #[Route('/{id}/edit/admin', name: 'app_pakege_edit_admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this->getUser();
        $wallet = $user -> getWallet();
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        
        $user_referral_link = $pakage_comet -> getReferralLink();
            $choise  = [
                'Starter' => 'Starter',
                'Basic' => 'Basic',
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        
        $unique = $pakage_comet -> getUniqueCode();
        $user_referral_link = $pakage_comet -> getReferralLink();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $form_pakage = $request->get('pakege')->getName();
            $form_referral_select = $request->get('select');
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_pakage]); 
            $wallet_cometpoin = $wallet -> getCometpoin();
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;
            $pakage_user_price = $pakage_user -> getPricePakage();
            $token = $token_rate * $pakage_user_price;
            if($form_referral_select == 1){

                if(($wallet -> getUsdt()) < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                else{
                    $new_balanse_wallet = $wallet -> getUsdt() - $pakage_user_price;
                    $wallet -> setUsdt($new_balanse_wallet);
                }
            }
            elseif($form_referral_select == 2){
                if($wallet_cometcoin_rate < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_edit_admin', ['id' => $id], Response::HTTP_SEE_OTHER);
                }
                else{
                    $new_balanse_wallet = $wallet_cometcoin_rate - $pakage_user_price;
                    $wallet -> setCometpoin($new_balanse_wallet);
                }
            }
            $pakage_comet -> setPrice($pakage_user_price);
            $pakage_comet -> setName($form_pakage);
            $pakage_comet -> setToken($token);
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                'Вы успешно изменили данные пакета');
            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_pakege_edit_admin', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/edit.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $unique,
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
            'choise' => $choise,
            'user_referral_link' => $user_referral_link,
        ]);
    }


    #[Route('/{id}', name: 'app_pakege_delete', methods: ['POST'])]
    public function delete(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete'.$pakege->getId(), $request->request->get('_token'))) {
            $pakegeRepository->remove($pakege);
            $this->addFlash(
                'danger',
                'Вы успешно удалили пакет, на электронную почту отправлено подтверждение операции');
        }

        return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
    }

    //#[Route('/new/{unique}/choice', name: 'app_pakege_new_choice', methods: ['GET', 'POST'])]
    private function newChoice ($request, $pakegeRepository, $doctrine, $unique,$wallet,$form_referral_select,$form_pakage_name,$pakage_user_price,$token_rate,$user_table)
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        
        $personal_data_table = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
        $client_code = $personal_data_table -> getClientCode();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['unique_code' => $unique]);
        $pakage_comet_name = $pakage_comet -> getName();
        $pakage_comet_id = $pakage_comet -> getId();

        if($form_referral_select == 1){
            $current_usdt = $wallet -> getUsdt();
            $new_balance_usdt = $current_usdt - $pakage_user_price;
            $wallet -> setUsdt($new_balance_usdt);
        }
        elseif($form_referral_select == 2){
            $current_cometpoin = $wallet -> getCometpoin();
            $new_balance_cometpoin = $current_cometpoin - ($pakage_user_price * $token_rate);
            $wallet -> setCometpoin($new_balance_cometpoin);
        }
            
        $pakage_table = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $pakage_comet_name]);
        $token_table =  $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();

        $price_usdt = $pakage_table -> getPricePakage();
        $price_token = $price_usdt * $token_table;
         
        $user_table -> setPakageStatus(1);
        
        $pakage_comet -> setUserId($user_id);
        $pakage_comet -> setPrice($price_usdt);
        $pakage_comet -> setToken($price_token);
        $pakage_comet -> setClientCode($client_code);
        //$pakage_comet->setCreatedAt(new \DateTimeImmutable());

        return $pakage_comet_id;
    }


    public function random_string ($str_length)
    {
    $str_characters = array (0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	// Функция может генерировать случайную строку и с использованием кириллицы
    //$str_characters = array (0,1,2,3,4,5,6,7,8,9,'а','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','э','ю','я');

    // Возвращаем ложь, если первый параметр равен нулю или не является целым числом
    if (!is_int($str_length) || $str_length < 0)
    {
        return false;
    }

    // Подсчитываем реальное количество символов, участвующих в формировании случайной строки и вычитаем 1
    $characters_length = count($str_characters) - 1;

    // Объявляем переменную для хранения итогового результата
    $string = '';

    // Формируем случайную строку в цикле
    for ($i = $str_length; $i > 0; $i--)
    {
        $string .= $str_characters[mt_rand(0, $characters_length)];
    }

    // Возвращаем результат
    return $string;
    }
}
