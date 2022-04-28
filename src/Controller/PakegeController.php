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
use App\Repository\SavingMailRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pakege')]
class PakegeController extends AbstractController
{
    #[Route('/admin', name: 'app_pakege_index_admin', methods: ['GET'])]
    public function index(PakegeRepository $pakegeRepository, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
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
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
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

    #[Route('/{client_code}/index', name: 'app_pakege_index', methods: ['GET','POST'])]
    public function indexUser(PakegeRepository $pakegeRepository, Request $request, SavingMailRepository $savingMailRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, string $client_code): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $pakages_table = $entityManager->getRepository(Pakege::class)->findByExampleClientField($client_code);
        $setting_option = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $update_day = $setting_option -> getUpdateDay();
        $ff=$setting_option->getCreatedAt();
        $ddd = date_modify($ff, $update_day.'day');    
        $timestamp = $ddd->getTimestamp();
        $pakages_update = [];
        $pakages_noupdate = [];
        foreach($pakages_table as $pakage){
            $date_pakage =$pakage -> getCreatedAt();
            $date_pakage_timestamp = date_modify($date_pakage, $update_day.'day')-> getTimestamp();
            if($date_pakage_timestamp > time()){
                $pakages_update[] = $pakage;
            }
            else{
                $pakages_noupdate[] = $pakage;
            }
        }
        
        if($pakages_table == false){
            $this->addFlash(
                'warning',
                'У вас нет доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        elseif($pakages_table == 1){
            $this->addFlash(
                'warning',
                'У вас приобретен всего один пакет, страница не доступна');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        if($pakages_table[0] -> getUserId() != $this -> getUser() -> getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        //$pakages = $pakegeRepository->findAll();
        $pakages_array_price = [0];
        
        //стоимость пакета в ЮСДТ
        foreach($pakages_table as $pakage){
            $pakages_array_price[] = $pakage -> getPrice();
        }
        $pakage_summ_usdt = array_sum($pakages_array_price);
        //стоимость пакета в Кометакоин
        foreach($pakages_table as $pakage){
            $pakages_array_token[] = $pakage -> getToken();
        }
        $pakage_summ_token = array_sum($pakages_array_token);

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_pakege_index', ['client_code' => $client_code], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pakege/index_user.html.twig', [
            'pakeges' => $pakages_update,
            'count' => count($pakages_table),
            'controller_name' => 'Мои пакеты',
            'title' => 'Pakages',
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'pakage_summ_usdt' => $pakage_summ_usdt,
            'pakage_summ_token' => $pakage_summ_token,
            'setting_option' => $setting_option,
            'pakeges_noupdate' => $pakages_noupdate,
        ]);
    }

    
    #[Route('/new/purchase/{unique_code_get?}', name: 'app_pakege_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PakegeRepository $pakegeRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,ReferralNetworkRepository $referralNetworkRepository, SavingMailRepository $savingMailRepository, string $unique_code_get): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this -> getUser();
        $user_id = $user -> getId();
        if( $user -> getWallet() == NULL){
            $this->addFlash(
                'warning',
                'Вы заполнили не все данные, у вас пока нет кошелька, пройдите полную регитрацию');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        $wallet_id = $user -> getWallet() -> getId();

        //dd($wallet_id);
        // $pakage_table = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id' => $user_id]);
        // if($pakage_table == true){    
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
        // }
        
        //проверка наличия кошелька для покупки нового пакета
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);

        
        $wallet = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $wallet_id]);
        if($unique_code_get == 'not'){
            $pakege = new Pakege();
        }
        else{
            $pakege = new Pakege();
            $pakege_action = new Pakege();
        }
        
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        if($unique_code_get != 'not'){
            $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['unique_code' => $unique_code_get]);
            $name_pakage = $pakege_user -> getName();
        }
        else{
            $name_pakage = NULL;
        }
        
        $choise = $this -> choiseNew($name_pakage);

        $user = $this -> getUser();
        $user_referral_link = $user -> getReferralLink();
        if($user_referral_link == NULL){
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
        }
        
        $entityManager = $doctrine->getManager();
        
        $unique_code1 = $this->random_string(10);
        $unique_code2 = $this->random_string(10);
        if($unique_code_get == 'not'){
            $unique_code = $unique_code1.$unique_code2;
        }
        else{
            $unique_code = $unique_code_get;
        }
        
        

        if ($form->isSubmitted() && $form->isValid()) {
            //$form_referral_link = $form->get('referral_link')->getData();
            //$form_pakage_name = $form->get('name')->getData();
            $form_referral_select = $request->get('select');
            $form_referral_select2 = $request->get('select2');
            
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_referral_select]); 
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $wallet_cometpoin = $wallet -> getCometpoin();
            $wallet_bitcoin = $wallet -> getBitcoin();
            $wallet_etherium = $wallet -> getEtherium();
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;
            $wallet_bitcoin_rate =  $wallet_bitcoin * 40000;
            $wallet_etherium_rate =  $wallet_etherium * 4000;
            $pakage_user_price = $pakage_user -> getPricePakage();

            // if($wallet -> getUsdt() < $pakage_user_price || $wallet_cometcoin_rate < $pakage_user_price || $wallet_bitcoin_rate < $pakage_user_price || $wallet_etherium_rate < $pakage_user_price ){
            //         $this->addFlash(
            //             'warning',
            //             'У вас недостаточно средств для приобретения пакета, пожалуйста пополните кошелек или выберите другой пакет');
            //         return $this->redirectToRoute('app_pakege_new', ['unique_code_get' => $unique_code_get], Response::HTTP_SEE_OTHER);
            //     }
            
            if($this -> possibilityCheck($form_referral_select2, $wallet, $pakage_user_price, $token_rate) == false){
                $this->addFlash(
                'warning',
                'У вас недостаточно средств для повышения уровня пакета, пожалуйста свой пополните кошелек или выберите меньшую сумму');
            return $this->redirectToRoute('app_pakege_new', ['unique_code_get' => $unique_code_get], Response::HTTP_SEE_OTHER);
            }
            
            // if($form_referral_link != NULL){
            //     if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $form_referral_link]) == false ){
                
            //     $this->addFlash(
            //         'danger',
            //         'Вы ошиблись при введении ссылки или ввели устаревшую ссылку, пожалуйста попробуйте еще раз');
            //     return $this->redirectToRoute('app_pakege_new', ['unique_code_get' => $unique_code_get], Response::HTTP_SEE_OTHER);
            //     }
            // }
            // elseif($form_referral_link == NULL){
            //     $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
            // }
            $token_table =  $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $personal_data_table = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user_id]);
            $client_code = $personal_data_table -> getClientCode();
            
                $pakege -> setReferralLink($user_referral_link); 
                $pakege -> setCreatedAt(new \DateTime());
                $pakege -> setUpdatedAt(new \DateTime());
                $pakage_table = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_referral_select]);
                $price_usdt = $pakage_table -> getPricePakage();
                $price_token = $price_usdt * $token_table;
                $user_table -> setPakageStatus(1);
            
                $pakege -> setUserId($user_id);
                $pakege -> setPrice($price_usdt);
                $pakege -> setName($form_referral_select);
                $pakege -> setUniqueCode($unique_code);
                if($unique_code_get != 'not')
                {
                    $pakege -> setAction(2);//код пакета приобретенного по акции
                }
                else{
                    $pakege -> setAction(0);//код пакета приобретенного по акции
                }
                $pakege -> setToken($price_token);
                $pakege -> setClientCode($client_code);   
                $pakegeRepository->add($pakege);
                $pakage_id = $entityManager->getRepository(Pakege::class)->findOneBy(['unique_code' => $unique_code])->getId(); 
                $user_table -> setPakageId($pakage_id);
            
            if($unique_code_get != 'not'){
                //подарочный пакет
                $pakege_action -> setReferralLink($user_referral_link); 
                $pakege_action -> setCreatedAt(new \DateTime());
                $pakege_action -> setUpdatedAt(new \DateTime());
                $pakage_table_action = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $name_pakage]);
                $price_usdt_action = $pakage_table_action -> getPricePakage();
                $price_token_action = $price_usdt * $token_table;
                $user_table -> setPakageStatus(1);
                $pakege_action -> setUserId($user_id);
                $pakege_action -> setPrice($price_usdt_action);
                $pakege_action -> setName($form_referral_select);
                $pakege_action -> setUniqueCode($unique_code);
                $pakege_action -> setToken($price_token_action);
                $pakege_action -> setAction(3);//код подарочного пакета
                $pakege_action -> setClientCode($client_code);   
                $pakegeRepository->add($pakege_action);
                $pakege_user -> setAction(1);//код пакета относительного которого покупался новый пакет по акции и подарен подарочный пакет
                $entityManager->flush();
            }
            if($form_referral_select2 == 0){
                $current_usdt = $wallet -> getUsdt();
                $new_balance_usdt = $current_usdt - $pakage_user_price;
                $wallet -> setUsdt($new_balance_usdt);
                $entityManager->flush();
            }
            elseif($form_referral_select2 == 1){
                $current_cometpoin = $wallet -> getCometpoin();
                $new_balance_cometpoin = $current_cometpoin - ($pakage_user_price * $token_rate);
                $wallet -> setCometpoin($new_balance_cometpoin);
                $entityManager->flush();
            }
            elseif($form_referral_select2 == 2){
                $current_bitcoin = $wallet -> getBitcoin();
                $new_balance_bitcoin = $current_bitcoin - ($pakage_user_price / 40000);
                $wallet -> setBitcoin($new_balance_bitcoin);
                $entityManager->flush();
            }
            elseif($form_referral_select2 == 3){
                $current_etherium = $wallet -> getEtherium();
                $new_balance_etherium = $current_etherium - ($pakage_user_price / 4000);
                $wallet -> setEtherium($new_balance_etherium);
                $entityManager->flush();
            }
            //$unique = $form->get('unique_code')->getData();
            //$pakage_comet_id = $this -> newChoice ($request,$pakegeRepository,$doctrine,$unique,$wallet,$form_referral_select,$form_pakage_name,$pakage_user_price,$token_rate,$user_table);
            //$user_table -> setPakageId($pakage_comet_id);

            $entityManager->flush();
            $mailerController->sendEmail($mailer,$savingMailRepository);
            $this->addFlash(
                'success',
                'Вы успешно приобрели новый пакет.');
            $this->addFlash(
                'info',
                'Чтобы пакет начал работать вы должны активировать пакет!'); 
            $pakage_comet = $entityManager->getRepository(Pakege::class)->findByExampleClientField($client_code); 
            return $this->redirectToRoute('app_pakege_index', ['client_code' => $client_code], Response::HTTP_SEE_OTHER);   
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_pakege_index', ['client_code' => $client_code], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/new.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
            'choise' => $choise,
            'controller_name' => 'Приобретение пакета',
            'title' => 'Pakages buy',
        ]);
    }


    #[Route('/{id}/show', name: 'app_pakege_show', methods: ['GET', 'POST'])]
    public function show(Pakege $pakege,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, SavingMailRepository $savingMailRepository, int $id): Response
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
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_pakege_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pakege/show.html.twig', [
            'pakege' => $pakege,
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'controller_name' => 'Пакет',
            'title' => 'Pakages',
        ]);
    }



    #[Route('/show/multi', name: 'app_pakege_show_multiPakage', methods: ['GET', 'POST'])]
    public function showMultiPakage(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        
        //$user_all_inform = $entityManager->getRepository(User::class)->findOneByIdJoinedToPakege($user_id);
        // if($user_all_inform == false){
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }
        // if($user_id != $pakage_user -> getUserId()){
        //     $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }
        // $pakages = $user_all_inform -> getPakege();
        // $array_pakages = [];
        // foreach($pakages as $pakage){
        //     $array_pakages[] = $pakage;
        // }
        
        $client_code = $user -> getPesonalCode();
        $secret_code = $user -> getSecretCode();

        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $multi_pakage_day = $setting_opyions -> getMultiPakageDay();
        $name_multi_pakage = $setting_opyions -> getNameMultiPakage();
        $action = 0;
        $pakages = $entityManager->getRepository(Pakege::class)->findByPakageActionField($name_multi_pakage, $user_id, $multi_pakage_day,$action);//получение пакетов соответсвующих акции
        $action_pakage = [];
        $datetime1 = (new \DateTime()); //Получаем текущую дату
        $multi_pakage_day1 = date('Y-m-d');
        $timestamp = $datetime1->getTimestamp();
        $timestamp_multi_pakage_day = $multi_pakage_day->getTimestamp();
        $time_control = $timestamp_multi_pakage_day - $timestamp;

        
        
        //$datetime3 = new DateTime(date("H:i:s"));//Получаем текущее время
        //$datetime4 = new DateTime('23:59:59');//Время, до которого действует акция

        $interval1 = $datetime1->diff($multi_pakage_day);// Считаем разницу для года, месяца и дня
        foreach($interval1 as $start){
            $stop[] = $start;
        }
        //$interval2 = $datetime3->diff($datetime4); // И считаем разницу для времени
        // Здесь должна быть проверка, не отрицательный ли интервал, но это можно сделать    

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pakege/show_multiPakage.html.twig', [
            'pakeges' => $pakages,
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'controller_name' => 'Приобретение дополнительного пакета',
            'title' => 'multi_pakages',
            //'action_pakage' => $action_pakage_name,
            'time' => time(),
            'multipagake_day' => $multi_pakage_day,
            'interval1' => $interval1,
            'stop_action' => $stop,
            'name_multi_pakage' => $name_multi_pakage,
            'time_control' => $time_control,
            'name_pakage' => $name_multi_pakage,
            'client_code' => $client_code,
            'secret_code' => $secret_code,
        ]);
    }

    
    #[Route('/{id}/edit/routingpakage', name: 'app_pakege_edit_routing', methods: ['GET', 'POST'])]
    public function editRoutingPakage(EntityManagerInterface $entityManager, int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $client_code = $user -> getPesonalCode();
        $pakage = $entityManager->getRepository(Pakege::class)->findByExampleIdField($user_id);
    
        if($pakage == false){    
            $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
        }
        elseif(count($pakage) == 1){
            return $this->redirectToRoute('app_pakege_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        elseif(count($pakage) > 1){
            return $this->redirectToRoute('app_pakege_index', ['client_code' => $client_code], Response::HTTP_SEE_OTHER);
        }
    }


    #[Route('/{id}/edit', name: 'app_pakege_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        $user = $this->getUser();
        $wallet = $user -> getWallet();
        $user_id = $user -> getId();
        //получение пакетов пользователя
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        if($pakage_comet == false){
            $this->addFlash(
                'danger',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        if($pakage_comet -> getActivation() == NULL){
            $this->addFlash(
                'danger',
                'Пакет не активирован.Вы не можете повысить пакет прежде чем его активируйте!');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        if($user_id != $pakage_comet -> getUserId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $network_code = $pakage_comet -> getReferralNetworksId();
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $update_day = $setting_opyions -> getUpdateDay();
        $fast_start = $setting_opyions -> getFastStart();
        $payments_direct = $setting_opyions -> getPaymentsDirect();
        $payments_direct_fast = $setting_opyions -> getPaymentsDirectFast();
        $payments_singleline = $setting_opyions -> getPaymentsSingleline();
        
        //проверка срока активации пакета
        if($pakage_comet -> getUpdatedAt() != NULL){
            $datetime = $pakage_comet -> getUpdatedAt();
        }
        else{
            $datetime = $pakage_comet -> getCreatedAt();
        }
            
        date_modify($datetime, $update_day.'day');    
        $timestamp = $datetime->getTimestamp();
        if($timestamp < time()){
             $this->addFlash(
                'warning',
                'Вы не можете повысить уровень пакета, срок 30 календарных дней, предусмотренный для повышения пакета истек.');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }    
       
        //проверка срока быстрого старта и получения двойного бонуса Директ 20%
        if($pakage_comet -> getUpdatedAt() != NULL){
            $datetime_direct = $pakage_comet -> getUpdatedAt();
        }
        else{
            $datetime_direct = $pakage_comet -> getCreatedAt();
        }

        $fast_start_timestamp = $fast_start -> getTimestamp();
        $timestamp_direct = $datetime_direct -> getTimestamp();
        
        if($fast_start_timestamp < $timestamp_direct){
            $k_direct = $payments_direct_fast;
        }
        else{
            $k_direct = $payments_direct;
        }    

        $form = $this->createForm(BoostPakageType::class, $pakege);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $choise_param_edit = $pakage_comet-> getName();
        $choise = $this -> choiseParamEdit($choise_param_edit);
        if($choise == false){
            $this->addFlash(
                'warning',
                'У вас самый высокий уровень пакета, вы не можете повысить пакет');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        $unique = $pakage_comet -> getUniqueCode();
        $user_referral_link = $pakage_comet -> getReferralLink();
        
        if ($form->isSubmitted() && $form->isValid()) {
            $form_pakage = $request->get('pakage');//назание пакета из формы
            $form_referral_select = $request->get('select');//название валюты оплаты из формы
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//курс токена
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_pakage]); //объект всех пакетов
            $wallet_cometpoin = $wallet -> getCometpoin();//получение суммы токенов на кошельке
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;//перевеод токенов в юсдт по курсу токена
            $pakage_user_price = $pakage_user -> getPricePakage();//стоимость выбранного пакета 
            $pakage_current_price = $pakage_comet-> getPrice();//стоимость текущего пакета
            $pakage_cost_difference = $pakage_user_price - $pakage_current_price;//разница стоимости пакетов
            $token = $token_rate * $pakage_user_price;
            if($this -> possibilityCheck($form_referral_select, $wallet, $pakage_cost_difference,$token_rate) == false){
                $this->addFlash(
                'warning',
                'У вас недостаточно средств для повышения уровня пакета, пожалуйста свой пополните кошелек или выберите меньшую сумму');
            return $this->redirectToRoute('app_pakege_edit', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            //начисления за повышения пакета 
            $array_bonus_refovod = $this -> chargeForPromotion($entityManager,$doctrine,$form_referral_select,$wallet,$pakage_cost_difference,$k_direct,$payments_singleline,$pakage_user_price,$wallet_cometcoin_rate,$id,$token_rate);
            
            //==================получаем и записываем  все начисления и погашеня сети ==============================================
            $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['network_code' => $network_code]);//обект родительской сети
            //получение текущих значений 
            //$listReferralNetwork -> setProfitNetwork($curren_network_summ);//общая сумма погашения пакетов на момент активации последнего пакета
            $listReferralNetwork_current_direct  = $listReferralNetwork -> getPaymentsDirect();//общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
            $listReferralNetwork_current_cash  = $listReferralNetwork -> getPaymentsCash();//общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
            $listReferralNetwork_current_balance  = $listReferralNetwork -> getCurrentBalance();//текущая общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета

            //новые значения
            $user_refovod_direct_bonus = $array_bonus_refovod[0];
            $user_refovod_cash_bonus = $array_bonus_refovod[1];
            $listReferralNetwork_new_direct = $user_refovod_direct_bonus + $listReferralNetwork_current_direct;
            $listReferralNetwork_new_cash = $user_refovod_cash_bonus + $listReferralNetwork_current_cash;
            $listReferralNetwork_new_balance = $pakage_cost_difference + $listReferralNetwork_current_balance;
            //dd($listReferralNetwork_new_direct);
            //запись данных начислений во всей сети в родительский объект сети
            //$listReferralNetwork -> setProfitNetwork($curren_network_summ);//обновленная общая сумма погашения пакетов на момент активации последнего пакета
            $listReferralNetwork -> setPaymentsDirect($listReferralNetwork_new_direct);// обновленная общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
            $listReferralNetwork -> setPaymentsCash($listReferralNetwork_new_cash);//обновленная общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
            $listReferralNetwork -> setCurrentBalance($listReferralNetwork_new_balance);//новая  общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета
            $listReferralNetwork -> setUpdatedAt(new \DateTime());

            //=========== =========================================================== ==============================================
            $pakage_comet -> setPrice($pakage_user_price);
            $pakage_comet -> setName($form_pakage);
            $pakage_comet -> setToken($token);
            $pakage_comet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                'Вы успешно повысили уровень пакета, на электронную почту отправлено подтверждение операции');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/edit_boost.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $unique,
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
            'choise' => $choise,
            'controller_name' => 'Повышение пакета',
            'title' => 'Pakage edit',
        ]);
    }


    #[Route('/{id}/edit/admin', name: 'app_pakege_edit_admin', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
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
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
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
            'controller_name' => 'Админ редактирование пакета',
            'title' => 'Admin Pakage edit',
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

        if($form_referral_select == 0){
            $current_usdt = $wallet -> getUsdt();
            $new_balance_usdt = $current_usdt - $pakage_user_price;
            $wallet -> setUsdt($new_balance_usdt);
        }
        elseif($form_referral_select == 1){
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


    private function random_string ($str_length)
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


    private function chargeForPromotion(EntityManagerInterface $entityManager,$doctrine,$form_referral_select,$wallet,$pakage_cost_difference,$k_direct,$payments_singleline,$pakage_user_price,$wallet_cometcoin_rate,$id,$token_rate){
        $entityManager = $doctrine->getManager();
        $referral_network_user = $entityManager -> getRepository(ReferralNetwork::class)->findOneBy(['pakege_id' => $id]);//объект владельца пакета
        $referral_link_refovod = $referral_network_user -> getMyTeam();
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
        $user_refovod -> setReward($user_refovod_reward_bonus_new);
        $user_refovod -> setDirect($user_refovod_direct_bonus_new);
        $user_refovod->setUpdatedAt(new \DateTime());
        //запись обновленных значений отчислений в сеть 
        $referral_network_user -> setPaymentsCash($new_balance_cash);//запись оьновленный значений отчислений в сеть по программе КешБек
        $referral_network_user -> setPaymentsNetwork($new_balance_direct);//запись оьновленный значений отчислений в сеть по программе Директ

        if($form_referral_select == 0){
                
            $new_balanse_wallet = $wallet -> getUsdt() - $pakage_cost_difference;//новый баланс кошелька
            $wallet -> setUsdt($new_balanse_wallet);
            $wallet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
        }
        elseif($form_referral_select == 1){
            //внутренний токен
            $new_balanse_wallet = ($wallet_cometcoin_rate - $pakage_cost_difference) * $token_rate;//новый баланс кошелька
            $wallet -> setCometpoin($new_balanse_wallet);
            $wallet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
            // $referral_network_user = $entityManager -> getRepository(ReferralNetwork::class)->findOneBy(['pakege_id' => $id]);//объект владельца пакета
            // $referral_link_refovod = $referral_network_user -> getMyTeam();
            // //dd($referral_network_user);
            // //получаем объект Рефовода и получаем текщие значения начислений бонусов
            // $user_refovod = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link_refovod]);
            // $user_refovod_curren_cash = $user_refovod -> getCash(); 
            // $user_refovod_curren_reward = $user_refovod -> getReward();
            // $user_refovod_curren_direct = $user_refovod -> getDirect();
            // $user_refovod_direct_bonus = ($pakage_cost_difference * $k_direct) / 100;
            // $user_refovod_cash_bonus = ($pakage_cost_difference * $payments_singleline) / 100;
            // $user_refovod_reward_bonus = $user_refovod_cash_bonus + $user_refovod_direct_bonus;
            // $user_refovod_direct_bonus_new = $user_refovod_curren_direct + $user_refovod_direct_bonus;
            // $user_refovod_cash_bonus_new = $user_refovod_curren_cash + $user_refovod_cash_bonus;
            // $user_refovod_reward_bonus_new = $user_refovod_curren_reward + $user_refovod_cash_bonus + $user_refovod_reward_bonus;
            // //получаем значения владельца пакета с текщим балансом и значениями отчисления в сеть на момент предыдущей активации пакета
            // $current_balance = $referral_network_user -> getBalance();//текущий баланс стоимости пакета пользователя в линии
            // $current_balance_direct = $referral_network_user -> getPaymentsNetwork();//текущий значение отчислений в сеть на момент последней активации пакета по программе Директ
            // $current_balance_cash = $referral_network_user -> getPaymentsCash();//текущий значение отчислений в сеть на момент последней активации пакета по программе CashBack
            // $balace_line_new = $current_balance + $pakage_cost_difference;//новый баланс стоимости пакета участника в линии
            // $new_balance_direct = $current_balance_direct + $user_refovod_direct_bonus;//обновленная сумма отчисления в линию по программе Директ на момент активации пакета
            // $new_balance_cash = $current_balance_cash + $user_refovod_cash_bonus;//обновленная сумма отчисления в линию по программе CashBack на момент активации пакета
            // $referral_network_user -> setBalance($balace_line_new);//запись нового баланса владельцу пакета
            // $referral_network_user -> setPakage($pakage_user_price);//запись нового значения стоимости нового пакета
            // $referral_network_user->setUpdatedAt(new \DateTime());
            // //запсиь новых начислений Рефоводу
            // $user_refovod -> setCash($user_refovod_cash_bonus_new); 
            // $user_refovod -> setReward($user_refovod_direct_bonus_new);
            // $user_refovod -> setDirect($user_refovod_reward_bonus_new);
            // $user_refovod->setUpdatedAt(new \DateTime());
            // //запись обновленных значений отчислений в сеть 
            // $referral_network_user -> setPaymentsCash($new_balance_cash);//запись оьновленный значений отчислений в сеть по программе КешБек
            // $referral_network_user -> setPaymentsNetwork($new_balance_direct);//запись оьновленный значений отчислений в сеть по программе Директ
        
        }
        elseif($form_referral_select == 2){
            //bitcoin
            $bitcoin_curret = $wallet -> getBitcoin();
            $new_balanse_wallet = $bitcoin_curret - ($pakage_cost_difference / 40000);//новый баланс кошелька
            $wallet -> setBitcoin($new_balanse_wallet);
            $wallet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
        }
        elseif($form_referral_select == 3){
            //etherium
            $etherium_curret = $wallet -> getEtherium();
            $new_balanse_wallet = $etherium_curret - ($pakage_cost_difference / 4000);//новый баланс кошелька
            $wallet -> setEtherium($new_balanse_wallet);
            $wallet -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
    }

    return $array_bonus_refovod = [$user_refovod_direct_bonus, $user_refovod_cash_bonus ];
    }
    

    private function choiseParamEdit($choise_param_edit){
        if($choise_param_edit == 'Starter'){
            $choise  = [
                'Basic' => 'Basic',
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($choise_param_edit == 'Basic'){
            $choise  = [
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($choise_param_edit == 'Networker'){
            $choise  = [
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($choise_param_edit == 'Business'){
            $choise  = [
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($choise_param_edit == 'Trader'){
            $choise  = [
                'VIP' => 'VIP',
            ]; 
        }
        elseif($choise_param_edit == 'VIP'){
            $choise = false;
        }
        return $choise;
    }

    private function choiseNew($name_pakage){
        if($name_pakage == NULL){
            $choise  = [
                'Starter'=> 'Starter',
                'Basic' => 'Basic',
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($name_pakage == 'Basic'){
            $choise  = [
                'Basic' => 'Basic',
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($name_pakage == 'Networker'){
            $choise  = [
                'Networker' => 'Networker',
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($name_pakage == 'Business'){
            $choise  = [
                'Business' => 'Business',
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        elseif($name_pakage == 'Trader'){
            $choise  = [
                'Trader' => 'Trader',
                'VIP' => 'VIP',
            ]; 
        }
        return $choise;
    }

    private function possibilityCheck($form_referral_select, $wallet, $pakage_cost_difference,$token_rate){
        //dd($form_referral_select);
        if($form_referral_select == 0){
            $control_summ = $wallet -> getUsdt();
            
        }
        elseif($form_referral_select == 1){
            $control_summ = $wallet -> getCometpoin() / $token_rate;
        }
        elseif($form_referral_select == 2){
            $control_summ = $wallet -> getBitcoin() * 40000;
        }
        elseif($form_referral_select == 3){
            $control_summ = $wallet -> getEtherium() * 4000;
        }
        //dd($control_summ);
        if($control_summ < $pakage_cost_difference){
            
            return false;
        }
        else{
            return true;
        }
    }
}
