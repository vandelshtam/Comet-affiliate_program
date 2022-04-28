<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\Wallet;
use App\Entity\TokenRate;
use App\Entity\SavingMail;
use App\Entity\PersonalData;
use App\Entity\SettingOptions;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\ReferralNetworkType;
use App\Form\ReferralToEmailType;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Entity\ListReferralNetworks;
use App\Repository\SavingMailRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/referral/network')]
class ReferralNetworkController extends AbstractController
{
    #[Route('/admin', name: 'app_referral_network_index', methods: ['GET', 'POST'])]
    public function index(ReferralNetworkRepository $referralNetworkRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
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
            return $this->redirectToRoute('app_referral_network_show', [], Response::HTTP_SEE_OTHER);
        }        
        return $this->renderForm('referral_network/index.html.twig', [
            'referral_networks' => $referralNetworkRepository->findAll(),
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Список всех участников сети',
            'title' => 'list users network',
        ]);
    }


    #[Route('/', name: 'app_referral_network_index_user', methods: ['GET', 'POST'])]
    public function indexUser(ReferralNetworkRepository $referralNetworkRepository,Request $request,  MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $entityManager = $doctrine->getManager();
        $referral_networks = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField($this->getUser()->getId());
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//курс токена
        if($referral_networks == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
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
        return $this->renderForm('referral_network/index_my_balances.html.twig', [
            'referral_networks' => $referral_networks,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Балансы моих пакетов',
            'title' => 'my balances',
            'token_rate' => $token_rate,
            'summ_direct' => $summ_direct,
            'summ_cash' => $summ_cash,
        ]);
    }


    #[Route('/{my_team}/myteam', name: 'app_referral_network_myteam', methods: ['GET'])]
    public function myTeam(Request $request, ReferralNetworkRepository $referralNetworkRepository,MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, SavingMailRepository $savingMailRepository, string $my_team): Response
    { 
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();
        if($entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]) == false){
            $this->addFlash(
                'warning',
                'У вас нет команды, вы никого не пригласили, или прав доступа');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }

        $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $this->getUser()->getId()]);
        if($referral_network_user -> getMyTeam() != $my_team){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        } 
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь) 
        $my_team_count = count($array_my_team);
        foreach($array_my_team as $array){
            $array_my_team_summ_pakege[] = $array -> getPakage();
        }
        $my_team_summ = array_sum($array_my_team_summ_pakege);

        $fast_consultation = new FastConsultation(); 
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('referral_network/index_my_team.html.twig', [
            'referral_networks' => $array_my_team,
            'fast_consultation_form' => $fast_consultation_form -> createView(),
            'my_team_summ' => $my_team_summ,
            'my_team_count' => $my_team_count,
            'controller_name' => 'Моя команда',
            'title' => 'my team',
        ]);
    }

    #[Route('/{referral_link}/{id}/new', name: 'app_referral_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReferralNetworkRepository $referralNetworkRepository, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, string $referral_link, int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();
        if($entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]) == false){
            $this->addFlash(
                'warning',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
        if($entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]) -> getUserId() != $this->getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
      
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['network_code' => $referral_link]);//выяснить необходим ли этот запрос????
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $user_id = $pakege_user -> getUserId();
        $arr = explode('-', $referral_link);//уникальный персональный код участника сети со статусом владелец сети преобразуем в массив для извлечения информации об участнике предоставившегго реферальную ссылку (рефовод)

        //создание уникального персонального кода  нового участника сети пришедшего по реферальной ссылке (рефовода)
        $arr1 = $arr[0]; $arr2 = $arr[2]; $arr3 = $arr[3];
        $member_code = $this -> makeMemberCode($arr1,$id,$arr2,$arr3);

        $referralNetwork = new ReferralNetwork();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        $user = $this -> getUser();
        $username = $user -> getUsername();
        $user_authentificate = $entityManager->getRepository(User::class)->findOneBy(['id' => $user -> getId()]);

        if ($form->isSubmitted() && $form->isValid()) {
            //проводим предварительное создание записи в таблицу строки нового участника реферальной сети 
            $referralNetwork -> setCreatedAt(new \DateTime());
            $referralNetworkRepository->add($referralNetwork);
            $user_authentificate -> setPakageStatus(1);
            $entityManager->flush();
            //запись нового участника в линию single_line
            $referral_network_id = $this -> newConfirm($request,$referralNetworkRepository, $doctrine,$member_code,$id,$referral_link);
            $this->addFlash(
                         'success',
                         'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');         
            return $this->redirectToRoute('app_referral_network_show', ['id' => $referral_network_id], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/new.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
            'username' => $username,
            'member_code' => $member_code,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Активация пакета',
            'title' => 'activation pakage',
        ]);
    }

    #[Route('/myplace', name: 'app_referral_network_myplace', methods: ['GET', 'POST'])]
    public function my_place(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();
        $referral_networks = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField($this->getUser()->getId());
        if($referral_networks == false){
            $this->addFlash(
                'danger',
                'Вы еще не приобрели и не активировали ни одного пакета, или у вас нет прав доступа.');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
        
        elseif ($referral_networks == true) {
            if(count($referral_networks) == 1){
                $id = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $this->getUser()->getId()]) -> getId();
                return $this->redirectToRoute('app_referral_network_show', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
            else{
                return $this->redirectToRoute('app_referral_network_index_user', [], Response::HTTP_SEE_OTHER);
            }    
        }
        else{
            $this->addFlash(
                'danger',
                'Вы еще не приобрели и не активировали пакет, или такого пользователя нет.');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
    }


    #[Route('/{id}/show', name: 'app_referral_network_show', methods: ['GET', 'POST'])]
    public function show(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine, SavingMailRepository $savingMailRepository, int $id,): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager = $doctrine->getManager();
        if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['id' => $id]) == false){
            $this->addFlash(
                'danger',
                'Вы еще не приобрели и не активировали ни одного пакета, или у вас нет прав доступа');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['id' => $id])->getUserId() != $this->getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['id' => $id]);
        $my_team = $referral_network -> getMyTeam();
        $referral_link = $referral_network -> getMemberCode();
        $user_id = $referral_network -> getUserId();
        $email_user = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]) -> getEmail();
        $wallet_id = $this -> getUser() -> getWallet() -> getId();
        
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField($referral_link);//получаем объект  участников моей команды (которых пригласил пользователь)
        
        $user_status = $referral_network -> getUserStatus();
        if($array_my_team != NULL){
            $my_team_count = count($array_my_team);
            foreach($array_my_team as $array){
                $array_my_team_summ_pakege[] = $array -> getPakage();
            }
            $my_team_summ = array_sum($array_my_team_summ_pakege);
        }
        else{
            $my_team_summ = 0;
            $my_team_count = 0;
        }
        
        $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);//объект владельца сети
        $user_id = $referral_network -> getUserId();
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id' => $user_id]);// получаем оъект пакета  участника реферальной сети
        $owner_array[] = $user_owner;//основатель сети (Владелец)
        $reward = $referral_network -> getReward();
        $pakage_price = $referral_network -> getPakage();
        $k_cash_back = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getCashBack()/100;//получаем коэффициент начисления cash_back
        $limit_cash_back = $k_cash_back * $pakage_price;
    
        //построение линии сингл-лайн в виде массива
        $single_line = array_merge($referral_network_left, $owner_array, $referral_network_right);//объеденяем в один массив в  соотвтетсвии с правилом построения линии сингл-лайн
        
        //получаем ключ положения пользователя в массиве объектов участников реферальной сети
        $j = 0;
        for($i = 0; $i < count($single_line); $i++){
            if($single_line[$i] -> getId() == $id){
                $j += 1;
                $key_user[] = $i;
                break;
            }
        }
        $single_line_left = [];
        for($i = 0; $i < $key_user[0]; $i++){
            $single_line_left[] = $single_line[$i];
        }
        $single_line_right = [];
        for($i = $key_user[0] + 1; $i < count($single_line); $i++){
            $single_line_right[] = $single_line[$i];
        }
        
        //получаем баланс левой и правой части линии
        $single_line_left_balance = [];
        for($i = 0; $i < count($single_line_left); $i++){
            $single_line_left_balance[] = $single_line_left[$i] -> getBalance();
        }
        $summ_single_line_left_balance = array_sum($single_line_left_balance);
        $count_single_line_left = count($single_line_left_balance);
        
        $single_line_right_balance = [];
        for($i = 0; $i < count($single_line_right); $i++){
            $single_line_right_balance[] = $single_line_right[$i] -> getBalance();
        }
        $summ_single_line_right_balance = array_sum($single_line_right_balance);
        $count_single_line_right = count($single_line_right_balance);

        $array_data = ['summ_left' => $summ_single_line_left_balance, 'summ_right' => $summ_single_line_right_balance,
                       'count_left' => $count_single_line_left, 'count_right' => $count_single_line_right,
                       'my_summ' => $reward];

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_referral_network_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }

        //раcчет - лимит вывода из сети (линии) на кошелек
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $withdrawal_wallet = $referral_network -> getWithdrawalToWallet();//учет ввсех сумм с накоплением выведенных из  сингллайн на кошелек
        $limit_wallet_from_line = $setting_opyions -> getLimitWalletFromLine();//коеф лимита для  вывода из сети на кошелек
        $reward_all = $referral_network -> getRewardWallet();//все начисления всети сингллайн за все время
        $available_amount = ($reward_all * $limit_wallet_from_line) / 100; //общая сумма доступная для вывода - контрольная с которой сравниваем выводимые средства
        $available_balance = $available_amount - $withdrawal_wallet;//доступный остаток для вывода в момент запроса

        $personal_data_id = $this->getUser()->getPersonalDataId();
        $personal_data_username = $entityManager->getRepository(PersonalData::class)->findOneBy(['id' => $personal_data_id]) -> getName();
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//курс токена
         
        $referral_link_to_email_form = $this->createForm(ReferralToEmailType::class);
        $referral_link_to_email_form->handleRequest($request);
        if ($referral_link_to_email_form->isSubmitted() && $referral_link_to_email_form->isValid()) {
            $email_to_client = $referral_link_to_email_form -> get('email_to_client')->getData();
            $mailerController -> sendReferralToEmail($mailer,$email_to_client,$email_user,$referral_link,$personal_data_username,$savingMailRepository); 
            return $this->redirectToRoute('app_referral_network_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('referral_network/show.html.twig', [
            'referral_network' => $referralNetwork,
            'data' => $array_data,
            'pakege_user' => $pakege_user,
            'k_cash_back' => $k_cash_back,
            'my_team_count' => $my_team_count,
            'my_team_summ' => $my_team_summ,
            'fast_consultation_form' => $fast_consultation_form,
            'referral_link_to_email_form' => $referral_link_to_email_form,
            'wallet_id' => $wallet_id,
            'limit_cashback' => $limit_cash_back,
            'available_balance' => $available_balance,
            'token_rate' => $token_rate,
            'title' => 'my balance',
            'controller_name' => 'Мой баланс',
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_referral_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository, MailerInterface $mailer,FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository,int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $network = $entityManager->getRepository(ReferralNetwork::class)->find($id);
        $username = $network -> getName();
        $member_code = $network -> getMemberCode();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralNetworkRepository -> setUpdatedAt(new \DateTime());
            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/edit.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
            'username' => $username,
            'member_code' => $member_code,
        ]);
    }

    #[Route('/{id}/admin', name: 'app_referral_network_delete', methods: ['POST'])]
    public function delete(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$referralNetwork->getId(), $request->request->get('_token'))) {
            $referralNetworkRepository->remove($referralNetwork);
        }
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
    }






    private function newConfirm($request,  $referralNetworkRepository,  $doctrine,  $member_code,  $id,  $referral_link,)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $entityManager = $doctrine->getManager();
        if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]) == false){
            $this->addFlash(
                'danger',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        // dd($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code])-);
        // if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code])->getUserId() != $this->getUser()->getId()){
        //  $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // }


        //==================== получение объектов и переменных для построения линии и обработки результата =====================
        // $id - АйДи нового пакета нового учатника который активируется в линии 
        //$referral_link реферральная ссылка нового участника полученная от пригласившего участника который называется  Рефовод
        //$member_code поле в таблице ReferralNetwork в которое записывается реферральная ссылка $referral_link. используя переменную $referral_link получаем из таблицы объект Рефовода, при запросе с использованием переменной $member_code - получаем объект нового участника пакет которого активируется 
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();//получем две самых новых по времени записи в реферальной сети (в таблице), предпоследняя запись содержит статус пользователя (left/right) для присвоения статуса новому пользователю
        $status_user = $referral_network_status[1]->getUserStatus();//получаем запись самого нового участника сети ,определяем его положение в линии "слева" или "справа"
        $status = $this -> status($status_user);// присваеваем новому участнику сети положение в линии  слева или справа
        $referral_network_referral = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//получаем объект пользователя участника  реферальной сети который предоставил реферальную ссылку Рефовод
        $pakege_id_refovod = $referral_network_referral -> getPakegeId();//АйДи пакета рефовода
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем данные нового участника в реферальной сети  чтобы дополнить информацию всеми необходимыми данными
        $network_code = $referral_network_referral -> getNetworkCode();//получаем индивидуальный идентификационный код родительской сети
        $list_network = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['network_code' => $network_code]);//обект родительской сети
        $list_network_all = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField([$network_code]);//все обекты родительской сети
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета нового участника реферальной сети
        $pakege_user_price = $pakege_user -> getPrice();//стоимость пакета нового участника
        $arr = explode('-', $member_code);//уникальный код участника сети преобразуем в массив для получения важных данных
        $listReferralNetwork_id = $arr[0];//айди реферальной сети
        $pakege_user_id = $arr[1];//айди пакета нового участника сети
        $user_id = $pakege_user -> getUserId();//айди нвого участника сети
        $balance = $pakege_user -> getPrice();//стоимость пакета нового участника сети
        $referral_network_id = $referral_network -> getId();//id записи нового участника реферральной сети 
        $list_network_all_count = count($list_network_all);
        
	    //получаем объект записи родительской реферальной сети и получем из нее код этой сети
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();
        // ==================================================================================================================

        //=============== первичная обработка информации и сохранение в таблицы ==============================================
        //изменения статуса пакета приглашенного участника сети на "активирован"
        $pakege_user -> setActivation('активирован');
        $pakege_user -> setReferralNetworksId($network_code);

        //построение первичной линии при количестве учстников менее 3-х
        //данные пользователя который предоставил реферальную ссылку
        $network_referral_id = $referral_network_referral -> getId();//айди записи участника реферальной сети предоставившего ссылку (рефовода)
        $user_referral_id = $referral_network_referral -> getUserId();//id пользователя системы участвующего в реферальной сети и предоставившего реферальную ссылку (рефовода)

        //расчет награды за приглашенного участника члену сети предоставишему реферальную ссылку (рефовода) DIRECT
        $setting_opyions = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $pakege_id_refovod]);
        $update_day = $setting_opyions -> getUpdateDay();
        $fast_start = $setting_opyions -> getFastStart();
        $payments_direct = $setting_opyions -> getPaymentsDirect();
        $payments_direct_fast = $setting_opyions -> getPaymentsDirectFast();
        $payments_singleline = $setting_opyions -> getPaymentsSingleline();
        $k_payments_singl_line = $payments_singleline / 100;
        //проверка срока быстрого старта и получения двойного бонуса Директ 20%
        if($pakage_comet -> getUpdatedAt() != NULL){
            $datetime = $pakage_comet -> getUpdatedAt();
            //date_modify($datetime, $fast_start.'day');
            $timestamp = $datetime->getTimestamp();
            $timestamp_fast_start = $fast_start->getTimestamp();
            
            if(time() < $timestamp_fast_start){
                $k_direct = $payments_direct_fast;
            }
            else{
                $k_direct = $payments_direct;
            }    
        }
        else{
            $datetime = $pakage_comet -> getCreatedAt();
            $timestamp = $datetime->getTimestamp();
            $timestamp_fast_start = $fast_start->getTimestamp();
            
            date_modify($datetime, $fast_start.'day');
            //dd(time() - $this->timestamp = $datetime->getTimestamp());
            if(time() < $timestamp_fast_start){
                $k_direct = $payments_direct_fast;
            }
            else{
                $k_direct = $payments_direct;
            }      
        }
       
        //начисления рефовода
        $bonus = ($balance * $k_direct) / 100;//direct начисление за приглашенного участника
       
        $referral_network_referral_bonus = $referral_network_referral -> getReward();//текущие начисления общие у рефовода
        $referral_network_referral_Rewardwallet = $referral_network_referral -> getRewardWallet();//текущие доступные общие начисления для вывода на кошелек у рефовода
        $referral_network_referral_direct = $referral_network_referral -> getDirect();//директ бонусы текущие у рефовода 
        $reward = $bonus + $referral_network_referral_bonus;
        $reward_wallet = $bonus + $referral_network_referral_Rewardwallet;//остаток начислений доступных для вывода на кошелек
        $direct = $bonus + $referral_network_referral_direct;
        $profit_network_advance = $balance - $bonus;
        
        $referral_network_referral -> setReward($reward);
        $referral_network_referral -> setDirect($direct);
        $referral_network_referral -> setRewardWallet($reward_wallet);
        $referral_network_referral -> setUpdatedAt(new \DateTime());
        $entityManager->flush();
        
        //записываем и сохраняем в таблицу нового участника реферальной сети и все дополнительные и обязательные данные
        $user = $this -> getUser();    
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus($status);
        $referral_network -> setPakegeId($pakege_user_id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setBalance($balance);//записываем баланс пользователя после активации его пакета, равно стоимости его пакета
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setUserReferralId($user_referral_id);
        $referral_network -> setNetworkReferralId($network_referral_id);
        $referral_network -> setMyTeam($referral_link);//код моей команды для подбора приглашенных пользователем новых пользователей
        $referral_network -> setPaymentsNetwork($bonus);//начисление дохода в сеть по программе Директ в момент активации пакета нового пользователя
        $referral_network -> setPakage($balance);//записываем стоимость пакета нового пользователя
        $referral_network -> setReward(0);//сумма начислений дохода пользователя в сети
        $referral_network -> setCash(0);//сумма начисления  дохода пользователю  по системе КэшБек
        $referral_network -> setDirect(0);//сумма начислений в сети пользователю по программе Директ
        $referral_network -> setCurrentNetworkProfit(0);//текщее отчисление  в доход проекта от погашения пакетов в момент активации пакета нвого пользователя
        $referral_network -> setPaymentsNetwork(0);//начисление в сеть по программе Директ в момент активации нового пакета
        $referral_network -> setPaymentsCash(0);//начисление в сеть по программе КешБек в момент активации нового пакета 
        $referral_network -> setRewardWallet(0);//остаток начислений доступных для вывода на кошелек пользователя
        $referral_network -> setWithdrawalToWallet(0);//общая сумма выведенных на кошелек начисленых доходов пользователя
        $referral_network -> setSystemRevenues(0);//сумма начисления дохода системы (30%) в момент активации пакета
        //$referral_network -> setCreatedAt(new \DateTime());
        //$referral_network -> setReward($reward);
        //$referral_network_referral -> setRewardWallet($reward);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referralNetworkRepository->add($referral_network);
        $old_profit_network = $list_network -> getProfitNetwork();
        $new_profit_network = $old_profit_network + $profit_network_advance;
        //$list_network ->setProfitNetwork($new_profit_network);
        $entityManager->flush();
        // ======================================================================================================================

        //===================выполнение формулы постоения  линии ================================================================ 
        $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();//количество участников в линии (в сети)
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);//получение всех объектов линии слевой стороны, пока в линии нет Владельца (Я)
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'right']);//получение всех объектов в линии справой стороны, в линии нет Владеьца (Я)!
        $referral_network_user_id = $referral_network_referral -> getId();// АйДи рефовода

        //первое построение линии из трех участников реферальной сети. Когда происходит активация пакета 3-го участника в сети числится еще 2 участника, поэтому в условии установлена цифра 2 участника активных 
        if($list_network_all_count == 1){
           
            $referral_network -> setPaymentsNetwork($direct);//начисление в сеть по программе Директ в момент активации нового пакета
            $entityManager->flush();
        }

        //первое построение линии из трех участников реферальной сети. Когда происходит активация пакета 3-го участника в сети числится еще 2 участника, поэтому в условии установлена цифра 2 участника активных 
        if($list_network_all_count == 2){
            $referral_network_user = $referral_network_referral;
            $this -> singleThree($referral_network_left,$referral_network_right,$referral_network_user,$old_profit_network,$list_network,$referral_network,$bonus,$doctrine, $payments_singleline,$pakege_user_price,$k_payments_singl_line);
            $entityManager->persist($referral_network);
            $entityManager->persist($referral_network_referral);
            $entityManager->flush();
        }

        //построение линии при количестве участников более 3-х. При активации 4 го участника и выше. Условие установлено более 3 ативных участников чьи пакету уже активированы
        elseif($list_network_all_count > 2){
            $referral_network_user = $referral_network_referral;
            $referral_network_user_new = $referral_network;
            $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
            $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);//объект владельца линии (Я)
            //обработка линии, проведение начислений при вступлении нового участника (активации новго пакета)
            $this -> single($request, $referralNetworkRepository, $referral_network_count, $user_owner, $doctrine,$referral_network_user, $referral_network_user_new, $referral_network, $member_code, $id, $referral_link,$profit_network_advance,$list_network,$network_code,$bonus, $balance,  $referral_network_referral, $payments_singleline);
            $entityManager->persist($referral_network);
            $entityManager->flush();
        }
        //======================================================================================================================


        //==================получаем и записываем  все начисления и погашеня сети ==============================================
        $list_network_all_new = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField([$network_code]);//обновляем все обекты родительской сети
        $curren_network = [];
        $payments_direct = [];
        $payments_cash = [];
        $current_price = [];
        $system_revenues = [];
        foreach($list_network_all_new as $curren_network_profit){
            $curren_network[] = $curren_network_profit -> getCurrentNetworkProfit();
        } 
        foreach($list_network_all_new as $payments_network){
            $payments_direct[] = $payments_network -> getPaymentsNetwork();
        } 
        foreach($list_network_all_new as $payments_network_cash){
            $payments_cash[] = $payments_network_cash -> getPaymentsCash();
        }
        foreach($list_network_all_new as $price_network){
            $current_price[] = $price_network -> getBalance();
        } 
        foreach($list_network_all_new as $system_revenues){
            $current_system_revenues[] = $system_revenues -> getSystemRevenues();
        }   
        $curren_network_summ = array_sum($curren_network);//общая сумма погашения пакетов на момент активации последнего пакета
        $payments_direct_summ = array_sum($payments_direct);//общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
        $payments_cash_summ = array_sum($payments_cash);//общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
        $current_price_summ = array_sum($current_price);//текущая общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета
        $current_system_revenues_summ = array_sum($current_system_revenues);//текущая общая сумма отчислений в доход системы (30%)

        //запись данных начислений во всей сети в родительский объект сети
        $listReferralNetwork -> setProfitNetwork($curren_network_summ);//общая сумма погашения пакетов на момент активации последнего пакета
        $listReferralNetwork -> setPaymentsDirect($payments_direct_summ);//общая сумма начислений попрограмме Директ на момент активации последнего пакета в сети
        $listReferralNetwork -> setPaymentsCash($payments_cash_summ);//общая сумма начислений по программе КешБек на момент активации последнего пакета в сети
        $listReferralNetwork -> setCurrentBalance($current_price_summ);//текущая общая сумма оставшихся не погашенных пакетов в сети на момент активации последнего пакета
        $listReferralNetwork -> setSystemRevenues($current_system_revenues_summ);//текущая общая сумма отчислений в доход системы (30%)
        $listReferralNetwork -> setUpdatedAt(new \DateTime());
        //=========== =========================================================== ==============================================

        // =====================================================================================================================
        //сохранение записей в базе данных
        $entityManager->persist($referral_network);
        $entityManager->persist($referral_network_referral);
        $entityManager->flush();
        
        return $referral_network_id;
    }

    
    private function status($status_user)
    {
            if($status_user == 'left')
            {
                $status_u = 'right';
            }
            else
            {
                $status_u = 'left';
            }
        return $status_u; 
    }


    public function single($request, ReferralNetworkRepository $referralNetworkRepository, $referral_network_count, $user_owner, $doctrine,$referral_network_user,$referral_network_user_new,$referral_network, $member_code, $id, $referral_link,$profit_network_advance,$list_network,$network_code,$bonus, $balance,  $referral_network_referral, $payments_singleline)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $entityManager = $doctrine->getManager();
        if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]) == false){
            $this->addFlash(
                'danger',
                'У вас нет прав доступа');
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code])->getUserId() != $this->getUser()->getId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        //==================формула расчета наград в сети при количестве участников 4 и более====================================
        //получаем информацию о сети и записи участника предоставившего реферальную ссылку(рефовода)
        
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии, запрос сделан так что объект строится в обратном порядке
        //так чтобы при соединении двух массивов Левой и Правой стороны получился один объект в котором все записи образут линию в центре которой от владельца (организатора сети) расходятся
        //в лево и в право участники в соответсвии с датой вступления, чем раньше дата , тем ближе к владельцу
        //$referral_network_left_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('left',0);//получаем объект участников с левой стороны линии с балансом более "0"
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        //$referral_network_right_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('right',0);//получаем объект участников с правой стороны линии с балансом более "0"
        $referral_network_all = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField(['network_code' => $network_code]);//получаем все  объекты сети
        //$referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByStatusField(['right',$network_code]);//получаем объект участников участников с правой стороны
        //$referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByStatusField(['left',$network_code]);//получаем объект всех участников с левой стороны линии 
        $pakege_all = $entityManager->getRepository(Pakege::class)->findByExampleField(['referral_networks_id' => $network_code]);//получаем все  объекты купленные пользователями в сети пакетов
        $price_pakage_all = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getAllPricePakage();//получаем параметр предельного баланса всех купленных пакетов для начисления выплат, если сеть достигла предела выплаты single-line не начисляются
        $k_payments_singl_line = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getPaymentsSingleline()/100;//получаем коеффициент начисления наград в  single-line 
        $k_payments_direct = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getPaymentsDirect();//получаем коэффициент начисления direct
        $k_cash_back = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getCashBack()/100;//получаем коэффициент начисления cash_back, в таблице запись в процентах
        $k_accrual_limit = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getAccrualLimit()/100;//получаем коэффициент общей суммы  предела начислений в линии в виде cash-back, в таблице запись в процентах
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $k_system_revenues = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getSystemRevenues();//коэффициент в процентах - доход системы , отчисляется всегда от баланса с меньшей стороны или если одни пакет то от стоимости пакета с меньшей стороны
        $user_referral_status = $referral_network_user -> getUserStatus();
        $owner_array[] = $user_owner;//основатель (владелец) сети

        //сумма стоимости всех приобретенных в сети пакетов
        foreach($pakege_all as $pakages){
            $pakages_summ[] = $pakages -> getPrice();
        }
        $all_pakages_summ = array_sum($pakages_summ) * $token_rate;//фактическая сумма стоимости приобретенных пакетов в сети переведенная в лакальный токен по курсу

        //====построение линии сингл-лайн и получение АйДи Рефовода и получение баланса с левой и с правой стороны ===============
        //получаем массив всех пользователей
        $single_line = array_merge($referral_network_left, $owner_array, $referral_network_right);//объеденяем в один массив в  соотвтетсвии с правилом построения линии сингл-лайн
        //находим порядкоый номер рефовода
        for($i = 0; $i <= count($single_line); $i++){
            if($single_line[$i] -> getMemberCode() == $referral_link){
                $key_user = $i;
                $single_line_id_refovod = $i;
                break;
            }
        }

        //относительно порядкового номера рефовода в массиве определяем линию с права и с лева
        $single_line_left = [];//массив линии с лева от Рефовода
        for($i = 0; $i < $key_user; $i++){
            $single_line_left[] = $single_line[$i];
        }
        $single_line_right = [];//массив линии с права от Рефовода
        for($i = $key_user + 1; $i < count($single_line); $i++){
            $single_line_right[] = $single_line[$i];
        }
        $array_single_line_right = $single_line_right;//массив линии с права от Рефовода без рефовода
        $array_single_line_left = $single_line_left;//массив линии с лева от Рефовода без рефовода
        
        //получаем баланс левой и правой части линии до  погашения баланса пакетов
        $single_line_left_balance = [];
        for($i = 0; $i < count($single_line_left); $i++){
            $single_line_left_balance[] = $single_line_left[$i] -> getBalance();
        }
        $summ_single_line_left_balance = array_sum($single_line_left_balance);//баланс пакетов в левой части линии относительно Рефовода
        
        $single_line_right_balance = [];
        for($i = 0; $i < count($single_line_right); $i++){
            $single_line_right_balance[] = $single_line_right[$i] -> getBalance();
        }
        $summ_single_line_right_balance = array_sum($single_line_right_balance);//баланс пакетов в правой части линии относительно Рефовода
        //==========================================================================================================================

        //============определяем с какой стороны линии сумма баланса больше и проводим начисления Кешбек ============================
        if($summ_single_line_left_balance == 0 || $summ_single_line_right_balance == 0){
            //проверка предельного баланса и сообщение в случае   достижения предела общего баланса сети
            if($price_pakage_all <= $all_pakages_summ){
                $this->addFlash(
                    'danger',
                    'Сеть достигла предела накопления пакетов Лимит стоимости пакетов сети');
            }
            else{
                //вычислим и проведем погашение балансов пакетов начисления погашения сумм в систему и начисление Дохода в систему( сечас 30%)
                //при таком сочетании стоимости пакетв в сети на момент активации нового пакета( когда баланс паветов справа и слева равны и не равны нулю)
                //начисляется только бонус Директ рефоводу, другим участникам линии ничего не начисляется, а пакеты в далнейшем сгорают
                //$cash_bonus_refofod = $this -> where_is_balance($referral_network_user,$summ_single_line_right_balance,$payments_singleline,$referral_network);//так как баланс с обоих сторон одинаковый для расчета можно взять любой баланс, в данной случае взят баланс линии с праваой стороны
                
                $repayment_amount = 0;//сумма погашения пакетов за минусом начисления Директ бонуса и Начисления в Дохода в систему (30%)
                $system_revenues = ($summ_single_line_right_balance * $k_system_revenues) / 100 ;//доход системы от суммы с одной стороны (30%)
                //$repayment_amount = ($summ_single_line_left_balance + $summ_single_line_right_balance) - ($bonus + $system_revenues + $cash_bonus_refofod);//сумма погашения которая уходит из сети в доход системы
                $referral_network -> setCurrentNetworkProfit($repayment_amount);// запись в таблицу начисления погашеной суммы в систему в момент активации пакета
                $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%) в момент активации пакета
                $referral_network -> setPaymentsNetwork($bonus);// запись в таблицу начисления  в момент активации пакета по граграмме Директ
                $entityManager->flush();
            }
            $this->addFlash(
                'danger',
                'Начисление  проведено по правилу когда с одной стороны 0');  
        }
        elseif($summ_single_line_left_balance == $summ_single_line_right_balance ){
            
            //проверка и вывод информационного сообщение о достижении предела общей стоимости сети (купленных пакетов)
            if($price_pakage_all <= $all_pakages_summ){
                    $this->addFlash(
                        'danger',
                        'Сеть достигла предела накопления пакетов Лимит стоимости пакетов сети');
                }
            else{
                //вычислим и проведем погашение балансов пакетов начисления погашения сумм в систему и начисление Дохода в систему( сечас 30%)
                //при таком сочетании стоимости пакетв в сети на момент активации нового пакета( когда баланс паветов справа и слева равны и не равны нулю)
                //начисляется только бонус Директ рефоводу, другим участникам линии ничего не начисляется, а пакеты в далнейшем сгорают
                $cash_bonus_refofod = $this -> where_is_balance($referral_network_user,$summ_single_line_right_balance,$payments_singleline,$referral_network);//так как баланс с обоих сторон одинаковый для расчета можно взять любой баланс, в данной случае взят баланс линии с праваой стороны
                $entityManager->flush();
                $repayment_amount = 0;//сумма погашения пакетов за минусом начисления Директ бонуса и Начисления в Дохода в систему (30%)
                $system_revenues = ($summ_single_line_right_balance * $k_system_revenues) / 100 ;//доход системы от суммы с одной стороны (30%)
                //всем участникам линии, кроме рефовода обнуляем баланс
                //обнуление баланса справа от рефовода
                while($i <= count($single_line_right)){
                    $user = array_shift($single_line_right);
                    $user -> setBalance(0);
                    $entityManager->flush(); 
                } 
                //обнуление баланса слева от рефовода
                while($i <= count($single_line_left)){
                    $user = array_shift($single_line_left);
                    $user -> setBalance(0);
                    $entityManager->flush(); 
                    }

                $repayment_amount = ($summ_single_line_left_balance + $summ_single_line_right_balance) - ($bonus + $system_revenues + $cash_bonus_refofod);//сумма погашения которая уходит из сети в доход системы
                $referral_network -> setCurrentNetworkProfit($repayment_amount);// запись в таблицу начисления погашеной суммы в систему в момент активации пакета
                $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%) в момент активации пакета
                $entityManager->flush();
            } 
            $this->addFlash(
                'danger',
                'Начисление  проведено по правилу одинаковогобаланса');  
                       
        }
        elseif($summ_single_line_left_balance != $summ_single_line_right_balance ){
            
                    $single_line_left = array_reverse($single_line_left);//переворачиваем массив в нормальный вид, чтобы перебор массива происходил от пользователей с более ранней датой входа в лини (которые в линии ближе к рефоводу)
                    //array_unshift($single_line_right, $referral_network_user);//добавляем рефовода который предоставил ссылку в массив с права 
                    $count_left = count($single_line_left);
                    $count_right = count($single_line_right);
                    $system_revenues = 0;//сумма дохода системы 30%

                    //высчитаем сумму от размера которой будут  производится начисления КешБек в линии Синг-Лайн и сравним с Лимитом для выплаты
                    //находим баланс с меньшей стороны и от этого баланса высчитываем предел выплаты в линию по программе КешБек (70%)
                    if($summ_single_line_left_balance > $summ_single_line_right_balance){
                        $summ_ammoutn_all = $summ_single_line_right_balance;
                        $system_revenues = ($summ_ammoutn_all * $k_system_revenues) / 100 ;//доход системы от баланса с меньшей стороны(30%)
                    }
                    else{
                        $summ_ammoutn_all = $summ_single_line_left_balance;
                        $system_revenues = ($summ_ammoutn_all * $k_system_revenues) / 100 ;//доход системы от баланса с меньшей стороны(30%)
                    }
                    

                    //запись начисления КешБек Рефоводу
                    $current_referral_user_reward = $referral_network_referral -> getReward();//общие текущие доходы
                    $current_referral_user_pakage = $referral_network_referral -> getPakage();//стоимость текущего пакета
                    $current_referral_user_reward_wallet = $referral_network_referral -> getRewardWallet();//общие доступные для перевода на кошелек доходы
                    $current_referral_user_cash = $referral_network_referral -> getCash();//общие текущие начисления СингЛайн
                   
                    $singl_line_limit = $k_cash_back * $current_referral_user_pakage;
                    if($current_referral_user_cash < $singl_line_limit ){
                        if($summ_single_line_left_balance < $summ_single_line_right_balance){
                            $user_referral_cashback_bonus_control = $summ_single_line_left_balance * $k_payments_singl_line;//наичисление СинглЛайн за приглашенного участника
                        
                                if($user_referral_cashback_bonus_control <= $singl_line_limit - $current_referral_user_cash){
                                    $user_referral_cashback_bonus = $user_referral_cashback_bonus_control;
                                }
                                else{
                                    $user_referral_cashback_bonus = $singl_line_limit - $current_referral_user_cash;
                                }
                                $user_referral_cashback = ($summ_single_line_left_balance * $k_payments_singl_line) + $current_referral_user_cash;//новый баланс КешБек бонуса рефовода (старый плюс новые начисления)
                                $user_referral_reward = ($summ_single_line_left_balance * $k_payments_singl_line) + $current_referral_user_reward;//новый баланс Всех начислений  рефовода (старые плюс новые начисления)
                                $new_user_referral_reward_wallet = $current_referral_user_reward_wallet + $user_referral_cashback_bonus;//новый баланс Всех начислений  рефовода доступные для вывода на кошелек
                                
                                $referral_network_referral -> setCash($user_referral_cashback);//запись   single-line КешБек рефоводу
                                $referral_network_referral -> setReward($user_referral_reward);//запись общего начисления поплнение общего количества начислений рефоводу  на момент автивации нового пакета
                                $referral_network_referral -> setRewardWallet($new_user_referral_reward_wallet);//запись общего начисления поплнение общего количества начислений рефоводудоступных для вывода на кошелек
                                $entityManager->flush();
                                
                        }
                        elseif($summ_single_line_left_balance > $summ_single_line_right_balance){
                            
                            $user_referral_cashback_bonus_control = $summ_single_line_right_balance * $k_payments_singl_line;
                            
                                if($user_referral_cashback_bonus_control <= $singl_line_limit - $current_referral_user_cash){
                                    $user_referral_cashback_bonus = $user_referral_cashback_bonus_control;
                                }
                                else{
                                    $user_referral_cashback_bonus = $singl_line_limit - $current_referral_user_cash;
                                }
                                $user_referral_cashback = ($summ_single_line_right_balance * $k_payments_singl_line) + $current_referral_user_cash;//новый баланс КешБек бонуса рефовода (старый плюс новые начисления)
                                $user_referral_reward = ($summ_single_line_right_balance * $k_payments_singl_line) + $current_referral_user_reward;//новый баланс Всех начислений  рефовода (старые плюс новые начисления)
                                $new_user_referral_reward_wallet = $current_referral_user_reward_wallet + $user_referral_cashback_bonus;//новый баланс Всех начислений  рефовода доступные для вывода на кошелек
                                $referral_network_referral -> setCash($user_referral_cashback);//запись   single-line КешБек рефоводу
                                $referral_network_referral -> setReward($user_referral_reward);//запись общего начисления поплнение общего количества начислений рефоводу  на момент автивации нового пакета
                                $referral_network_referral -> setRewardWallet($new_user_referral_reward_wallet);//запись общего начисления поплнение общего количества начислений рефоводудоступных для вывода на кошелек
                                $entityManager->flush();
                            
                        }
                    }
                    else{
                        $user_referral_cashback_bonus = 0;
                    }

                    $accrual_limit = ($summ_ammoutn_all) - ($user_referral_cashback_bonus + $system_revenues);//Доля начислений в линию по КешБек - максимальная сумму начисления по программе КешБек в линию(лимит совокупных начислений)
                    
                    //=====получение общей суммы начислений в линии в виде cash-back которые по 1-му правилу должны начисляться участникам
                    //==========вычисляем и записываем награды участникам лини, сначала двигаясь относительно Рефовода  в сторону меньшего баланса линии, если лимит не превшен начинаем движение в противополжную от меньшего баланса сторону ===========
                    //array_unshift($single_line_right, $referral_network_user);//!!!!!!!добавляем рефовода который предоставил ссылку в массив с права , так как сначала масивы участников строились слева и спава от рефовода, самого рефовода
                    //нет в массивах, теперь чтобы начислять награды двигаясь по линии и сравнивая балансы, рефовода нужно добавлять Рефовода в линию чтобы учитывать его баланс

                    $count_left = count($single_line_left);//количество участников слева от Рефовода
                    $count_right = count($single_line_right);//количество участников справа от Рефовода
                    
                    //условие начисления выплат по линии Сингл-Лайн в зависимсоти от количества проданных пакетов
                    if($price_pakage_all > $all_pakages_summ){ 
                        //условие начисления выплат по линии Сингл-Лайн если общее количество выплат меньше лимита суммы (сейчас 70% от суммы баланса с маньшей стороны) 
                        //то начисление производится в размере определенного коэффициентом (сейчас 10%), если общая сумма начислений к выплате превашает установленный лимит (70%) то включаем "Цикл начисления в линию"
                        //проверяем общее начисление и количество пользователей к начислению КешБэк в линии с помощью методов cashBackSummRight,cashBackSummLeft
                       
                        $cash_back_all_2 = $this -> cashBackSummRight($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline,$accrual_limit);
                        $cash_back_all_1 = $this -> cashBackSummLeft($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline,$accrual_limit);
                      
                        $cash_back_all_1_summ = array_sum($cash_back_all_1); 
                        $cash_back_all_2_summ = array_sum($cash_back_all_2);
                        $cash_back_all_left_count = count($cash_back_all_1); //количество участников которые не превысили норму отчислений 300% в линию КешБек с левой стороны
                        $cash_back_all_right_count = count($cash_back_all_2);//количество участников которые не превысили норму отчислений 300% в линию КешБек с правой стороны

                        $cash_back_all_summ = $cash_back_all_1_summ + $cash_back_all_2_summ;//совокупная расчетная сумма для начислений по программе КешБек по правилу №1 
                        $cash_back_all_count = $cash_back_all_left_count + $cash_back_all_right_count;//количество участников которым полагается выплата по правилу №1 

                        //расчет начислений КешБек в линию, если общая сумма начисления не превышает лимит выплаты (70%) то начисляем по правилу №1
                        //если сумма выплат превышает лимит то начисление проводим по правилу "Цикла"
                        
                        if($accrual_limit >= $cash_back_all_summ){
                            //теперь проделываем операции по определению наград двигаясь в  обе стороны  от Рефовода по линии по правилу №1 и общую сумму начисления в сеть
                            $all_cash_right = $this -> reward_single_right_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine, $count_left, $count_right,$k_cash_back, $payments_singleline, $referral_network_user,$k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count);
                            $all_cash_left = $this -> reward_single_left_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline, $referral_network_user,$k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count);
                            
                            if($price_pakage_all > $all_pakages_summ){   
                                $all_cash_right_summ = array_sum($all_cash_right);
                                $all_cash_left_summ = array_sum($all_cash_left);
                            }
                            else{
                                $all_cash_right_summ = 0;
                                $all_cash_left_summ = 0;
                            }
                            $payments_cash = $all_cash_right_summ + $all_cash_left_summ;//общая сумма начислений КешБек в сеть
                            $this->addFlash(
                                'danger',
                                'Начисление КешБек проведено поправилу №1'); 
                        }
                        elseif($accrual_limit < $cash_back_all_summ){
                            //сначала высчитаем конечную сумму выплаты по кешбэк каждому участнику про правилу "Цикла" и общую сумму выплаты в сеть
                            $payments_cash = $this -> cycleRule($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$referral_network_all, $k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count,$payments_singleline);
                            $this->addFlash(
                                'danger',
                                'Внимание! Начисление КешБек  проведено по равилу "Цикла"'); 
                        }
                            
                    } 
                    else{
                        $this->addFlash(
                            'danger',
                            'ВНИМАНИЕ! Сеть достигла предела накопления пакетов, выплат и начислений нет');
                    }       

                    //=====запись текущих начислений и выплат в сети ========
                    //условие начисления выплат по линии Сингл-Лайн в зависимсоти от количества проданных пакетов
                    // if($price_pakage_all > $all_pakages_summ){   
                    //         $all_cash_right_summ = array_sum($all_cash_right);
                    //         $all_cash_left_summ = array_sum($all_cash_left);
                    // }
                    // else{
                    //         $all_cash_right_summ = 0;
                    //         $all_cash_left_summ = 0;
                    // }

                    //$payments_cash - общая сумма начислений КешБек в сеть на момент активации пакета
                    $referral_network -> setPaymentsNetwork($bonus);//direct начисление  за текущиую итерацию сети (на момент активации нового пакета)
                    $referral_network -> setPaymentsCash($payments_cash);//запись общего начисления  single-line КешБек на момент автивации нового пакета

                    //вычисление суммы к погашению и зачислению в проект
                    $repayment_amount =0;//сумма погашения пакетов для начисления в систему 
                    //сумма  погашения пакетов в сети и начисления в систему
                    if($summ_single_line_left_balance < $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_left_balance * 2) - ($bonus + $payments_cash + $system_revenues);
                    }
                    elseif($summ_single_line_left_balance > $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_right_balance * 2) - ($bonus + $payments_cash + $system_revenues);
                    }
                    elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_left_balance + $summ_single_line_right_balance) - ($bonus + $payments_cash + $system_revenues);
                    }


                    //==============проводим погашение баланса пакетов пользователей в линии=============
                    //сформируем массивы баланса пакетов больше нуля с левой и с правой стороны
                    
                        $single_line_left_balance_pakege = [];
                        for($i = 0; $i < count($array_single_line_left); $i++){
                            if($array_single_line_left[$i] -> getBalance() > 0){
                                $single_line_left_balance_pakege[] = $array_single_line_left[$i];
                                $array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
                            }    
                        }
                       
                        $summ_left_balance_pakege = array_sum($array_left_balance_pakege);
                        $count_left_balance_pakege = count($array_left_balance_pakege);
                        
                        $single_line_right_balance_pakege = [];
                        for($i = 0; $i < count($array_single_line_right); $i++){
                            if($array_single_line_right[$i] -> getBalance() > 0){
                                $single_line_right_balance_pakege[] = $array_single_line_right[$i];
                                $array_right_balance_pakege[] = $array_single_line_right[$i] -> getBalance();
                            }
                        }
                        $summ_right_balance_pakege = array_sum($array_right_balance_pakege);
                        $count_right_balance_pakege = count($array_right_balance_pakege);
                        
                        //запись нвого баланса стоимости пакетов
                        if($summ_left_balance_pakege > $summ_right_balance_pakege){
                            $summ_remainder = $summ_left_balance_pakege - $summ_right_balance_pakege;

                            for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                                $balance_old1 = $single_line_left_balance_pakege[$i] -> getBalance();
                                $participation_rate = $balance_old1 / $summ_left_balance_pakege;
                                $single_line_left_balance_pakege[$i] -> setKoef($participation_rate);
                                $entityManager->flush();
                            }
                            for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                                $participation_rate_user = $single_line_left_balance_pakege[$i] -> getKoef();
                                $new_balance_user = $participation_rate_user * $summ_remainder;
                                $single_line_left_balance_pakege[$i] -> setBalance($new_balance_user);
                                $entityManager->flush();
                            }

                            for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                                $single_line_right_balance_pakege[$i] -> setBalance(0);
                                $entityManager->flush();
                            }
                            
                        }
                        else{
                            $summ_remainder2 = $summ_right_balance_pakege - $summ_left_balance_pakege;
                            for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                                
                                $balance_old2 = $single_line_right_balance_pakege[$i] -> getBalance();
                                $participation_rate2 = $balance_old2 / $summ_right_balance_pakege;
                                $single_line_right_balance_pakege[$i] -> setKoef($participation_rate2);
                                $entityManager->flush();
                            }
                            for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                                
                                $participation_rate_user2 = $single_line_right_balance_pakege[$i] -> getKoef();
                                $new_balance_user2 = $participation_rate_user2 * $summ_remainder2;
                                $single_line_right_balance_pakege[$i] -> setBalance($new_balance_user2);
                                $entityManager->flush();
                            }

                            for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                                $single_line_left_balance_pakege[$i] -> setBalance(0);
                                $entityManager->flush();
                            }
                        }    
                    $referral_network -> setCurrentNetworkProfit($repayment_amount);// запись в таблицу начисления погашаемой суммы в систему!!!!!!!!!!!!!!!!!!!!!!!!!!
                    $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%)
                    $entityManager->persist($referral_network);
                    $entityManager->flush();        
        }
        $entityManager->persist($referral_network);
        $entityManager->flush();
    }
    


    
    
    private function singleThree($referral_network_left,$referral_network_right,$referral_network_user,$old_profit_network,$list_network,$referral_network,$bonus,$doctrine,$payments_singleline, $pakege_user_price,$k_payments_singl_line)
    {
        $entityManager = $doctrine->getManager();
        $k_system_revenues = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getSystemRevenues();//коэффициент в процентах - доход системы , отчисляется всегда от баланса с меньшей стороны или если одни пакет то от стоимости пакета с меньшей стороны
        //первое выстраиваивание линии из двух участников реферальной сети  и начисление вознаграждений       
        if($referral_network_left -> getBalance() == $referral_network_right -> getBalance()){
            $balance_pakege_right = $referral_network_right -> getBalance();//баланс с права
            $balance_pred = $referral_network_left -> getBalance();// баланс с лева
            $cash_refovod = $referral_network_user -> getCash();//текущий КешБек рефовода
            $reward_user_wallet = $referral_network_user -> getRewardWallet();//текущие общие начисления рефовода доступные для перевода на кошелек
            $referral_network_left -> setBalance(0);
            $referral_network_right -> setBalance(0);
            //начисления рефовода
            $reward_user = $referral_network_user ->  getReward();//текущие общие начисления наград Рефовода
            //$direct_user = $referral_network_user ->  getDirect();//текущие начисления по программа Директ Рефовода
            $cash_bonus_refovod = ($balance_pred * $payments_singleline) / 100;// начисления КешБек Рефовода в линии
            $new_cash_refovod = $cash_refovod + $cash_bonus_refovod;//новые начисления Рефовода по программе КешБек
            $reward = $reward_user + $cash_bonus_refovod;//новое начисление общей суммы наград 
            $new_reward_wallet = $reward_user_wallet + $cash_bonus_refovod;//новый общий баланс Рефовода доступный для перевода на кошелек
            $referral_network_user ->  setReward($reward);//обновление общих наград Рефовода
            $referral_network_user -> setCash($new_cash_refovod);//запись нового КешБек Рефовода
            $referral_network_user ->  setRewardWallet($new_reward_wallet);//запись нового остатка начислений доступных для вывода на кошелек пользователя
            //начисления в  систему (доход)
            $system_revenues = ($balance_pakege_right * $k_system_revenues) / 100; //баланс с любой стороны умножаем на коэфициент выплаты дохода в сеть (30%)  получаем сумму начисления в систему как дох
            $repayment_amount = ($balance_pred + $balance_pakege_right) - ($bonus + $system_revenues + $cash_bonus_refovod);//сумма погашения пакетов которая наисляется в доход системы
            //$list_network ->setProfitNetwork($new_profit_network);
            $payments = $bonus;
            $referral_network -> setCurrentNetworkProfit($repayment_amount);// запись в таблицу начисления погашаемой суммы в систему
            $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%)
            $referral_network -> setPaymentsNetwork($payments);//запись начисления по программе Директ на момент активации пакета
            $referral_network -> setPaymentsCash($cash_bonus_refovod);//начисление в сеть по программе КешБек в момент активации пакета
        }
        elseif($referral_network_left -> getBalance() < $referral_network_right -> getBalance()){
            $balance_pred_left = $referral_network_left -> getBalance();//сумма с лева
            $balance_pred_right = $referral_network_right -> getBalance();//сумма с права
            $system_revenues = ($balance_pred_left * $k_system_revenues) / 100; //баланс с меньшей стороны умножаем на коэфициент выплаты дохода в сеть (30%)  получаем сумму начисления в систему как дохода
            $repayment_balance = $balance_pred_left * 2;//сумма погашения баланс с севой стороны (с меньшей) умножаем на 2
            $cash_refovod = $referral_network_user -> getCash();//текущий баланс КешБек Рефовода
            $balance_right = $balance_pred_right - $balance_pred_left;// остаток баланса остающийся в линии после погашения
            $referral_network_left -> setBalance(0);// с меньшей стороны обнуляем баланс
            $referral_network_right -> setBalance($balance_right);//в большую сторону в право записываем новый остаток баланса после погашения
            $reward_user = $referral_network_user -> getReward();//текщие общие начисления наград Рефовода
            $reward_user_wallet = $referral_network_user -> getRewardWallet();//текущие общие начисления рефовода доступные для перевода на кошелек
            $cash_bonus_refovod = ($balance_pred_left * $payments_singleline) / 100;// начисления КешБек Рефовода в линии
            $reward = $reward_user + $cash_bonus_refovod;//новый общий баланс Рефовода с учетом КешБек
            $new_reward_wallet = $reward_user_wallet + $cash_bonus_refovod;//новый общий баланс Рефовода доступный для перевода на кошелек
            $new_cash_refovod = $cash_refovod + $cash_bonus_refovod;//новый баланс КешБек Рефовода
            $referral_network_user ->  setReward($reward);//запись нового общего баланса 
            $referral_network_user ->  setRewardWallet($new_reward_wallet);//запись нового остатка начислений доступных для вывода на кошелек пользователя
            $referral_network_user -> setCash($new_cash_refovod);//запись нового баланса КешБек  Рефовода
            $new_profit_network = $repayment_balance - ($bonus + $cash_bonus_refovod + $system_revenues);
            
            //запись общих сумм на момент активации пакета в линии
            $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%)
            $referral_network -> setPaymentsNetwork($bonus);//начисление в сеть попрограмме Директ в момент активации нового пакета
            $referral_network -> setPaymentsCash($cash_bonus_refovod);//начисление в сеть по программе КешБек в момент активации пакета
            $referral_network ->setCurrentNetworkProfit($new_profit_network);//запись в таблицу погашенной суммы пакетов
        }
        elseif($referral_network_left -> getBalance() > $referral_network_right -> getBalance()){
            $balance_pred_left = $referral_network_left -> getBalance();//сумма с лева
            $balance_pred_right = $referral_network_right -> getBalance();//сумма с права
            $system_revenues = ($balance_pred_left * $k_system_revenues) / 100; //баланс с меньшей стороны умножаем на коэфициент выплаты дохода в сеть (30%)  получаем сумму начисления в систему как дохода
            $repayment_balance = $balance_pred_right * 2;//сумма погашения баланс с севой стороны (с меньшей) умножаем на 2
            $cash_refovod = $referral_network_user -> getCash();//текущий баланс КешБек Рефовода
            $balance_left =  $balance_pred_left - $balance_pred_right;// остаток баланса остающийся в линии после погашения
            $referral_network_right -> setBalance(0);// с меньшей стороны обнуляем баланс
            $referral_network_left -> setBalance($balance_left);//в большую сторону в право записываем новый остаток баланса после погашения
            $reward_user = $referral_network_user -> getReward();//текщие общие начисления наград Рефовода
            $reward_user_wallet = $referral_network_user -> getRewardWallet();//текущие общие начисления рефовода доступные для перевода на кошелек
            $cash_bonus_refovod = ($balance_pred_left * $payments_singleline) / 100;// начисления КешБек Рефовода в линии
            $reward = $reward_user + $cash_bonus_refovod;//новый общий баланс Рефовода с учетом КешБек
            $new_reward_wallet = $reward_user_wallet + $cash_bonus_refovod;//новый общий баланс Рефовода доступный для перевода на кошелек
            $new_cash_refovod = $cash_refovod + $cash_bonus_refovod;//новый баланс КешБек Рефовода
            $referral_network_user ->  setReward($reward);//запись нового общего баланса 
            $referral_network_user ->  setRewardWallet($new_reward_wallet);//запись нового остатка начислений доступных для вывода на кошелек пользователя
            $referral_network_user -> setCash($new_cash_refovod);//запись нового баланса КешБек  Рефовода
            $new_profit_network = $repayment_balance - ($bonus + $cash_bonus_refovod + $system_revenues);
            
            //запись общих сумм на момент активации пакета в линии
            $referral_network -> setSystemRevenues($system_revenues);// запись в таблицу начисления  суммы дохода системы  (30%)
            $referral_network -> setPaymentsNetwork($bonus);//начисление в сеть попрограмме Директ в момент активации нового пакета
            $referral_network -> setPaymentsCash($cash_bonus_refovod);//начисление в сеть по программе КешБек в момент активации пакета
            $referral_network ->setCurrentNetworkProfit($new_profit_network);//запись в таблицу погашенной суммы пакетов
        } 
             
    }

    private function where_is_balance($referral_network_user,$summ_single_line_right_balance,$payments_singleline,$referral_network){
        //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
        $reward_refovod = $referral_network_user -> getReward();//общие текущеи начисления 
        $reward_user_wallet = $referral_network_user -> getRewardWallet();//общие остаточные начисления доступные для вывода зп минусом уже выведенных
        $cash_refovod = $referral_network_user -> getCash();//текущие начисления КешБек
        $reward_right_user = ($summ_single_line_right_balance * $payments_singleline) / 100;//КешБек Рефовода = контрольная сумма баланса одной из сторон (правая) линии по которой начисляются награды
        $new_reward_user = $reward_refovod + $reward_right_user;//обновленные общие начисления в линии 
        $new_reward_wallet = $reward_user_wallet + $reward_right_user; //обновленные общие начисления доступные для вывода на кошелек
        $new_cash_refovod = $cash_refovod + $reward_right_user;//обновленные начисления КешБек
        $referral_network_user -> setReward($new_reward_user);
        $referral_network_user ->  setRewardWallet($new_reward_wallet);
        $referral_network_user -> setCash($new_cash_refovod);
        $referral_network -> setCash($reward_right_user);//запись начислений в линии КешБек на момент активации пакета 
        return $reward_right_user;
    }

    private function reward_single_right_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$payments_singleline, $referral_network_user,$k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count){
        //далее начинаем начисление наград участникам линии двигаясь в правую сторону перебирая массив участников 
        //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей суммы справа или слева,
        //если баланс имеется, то заново определяем баланс с каждой стороны, орпеделяем на какой стороне баланс меньше, начисляем награду от меньшей суммы
        //чтобы учесть возможное наличие баланса у Рефовода добавим его в левую часть линии

        $i = 0;
        $single_line_right_r = $single_line_right;
        $single_line_left_r = $single_line_left;
        array_unshift($single_line_left_r, $referral_network_user);//!!!!!!!добавляем рефовода в левую часть линии
        $cash_all = [];
        $control_all_summ = 0;
        while($i < count($single_line_right_r))
        {
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_right_r);// убираем одного пользователя с левой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $current_cash = $user -> getCash();//текущие награды SinglLine  каждого юзера вызанного из массива
                $reward_wallet = $user -> getRewardWallet();//текущие доступные суммы для перевода на кошелек
                $user_id_pakege = $user -> getPakage();// стоимость пакета пользователя
                $limit_cash_back = $k_cash_back * $user_id_pakege;//лимит наисления дохода от СинглЛайн


                //перебираем массив с лева относительно каждого пользователя которому делаем расчет начислений наград при движении по линии чтобы определить баланс линии относительнонего
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left_r); $j++){
                    $single_line_left_balance_new[] = $single_line_left_r[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);

                //перебираем массив с права относительно каждого пользователя которому делаем расчет начислений наград при движении по линии чтобы определить баланс линии относительнонего
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right_r); $k++){
                    $single_line_right_balance_new[] = $single_line_right_r[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);
                

                //определяем с какой стороны баланс меньше и проводим начисление наград
                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_right_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды

                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                    $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }

                    //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                    
                    if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                        
                        $new_cash = $current_cash + $cash_user_bonus;
                        $user -> setCash($new_cash); 
                        $reward_user = $cash_user_bonus + $reward;
                        $reward_user_wallet = $cash_user_bonus + $reward_wallet;  
                        $user -> setReward($reward_user);
                        $user -> setRewardWallet($reward_user_wallet);
                        $cash_all[] = $cash_user_bonus;
                    }
                    
                    $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance < $summ_single_line_right_balance){

                    $cash_user_single_control = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
    
                    //$cash_refovod2 = $user -> getCash();
                    //$user_id_pakege = $user -> getPakage();
                    //$reward_wallet = $user -> getRewardWallet();
                    //$limit_cash_back = $k_cash_back * $user_id_pakege;
                    //$reward2 = $user -> getReward();
                    if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                        
                        $new_cash = $current_cash + $cash_user_bonus;
                        $user -> setCash($new_cash); 
                        $reward_user = $cash_user_bonus + $reward;
                        $reward_user_wallet = $cash_user_bonus + $reward_wallet;  
                        $user -> setReward($reward_user);
                        $user -> setRewardWallet($reward_user_wallet);
                        $cash_all[] = $cash_user_bonus;
                    }
                    
                    $entityManager->flush();   
                } 
                elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                    $reward_user_new2 = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    //ничего не начисляем 
                } 
                array_unshift($single_line_left_r, $user);//добавляем  пользователя  в массив с левой стороны
                $control_all_summ += $cash_user_bonus;
        }
        return $cash_all;
    } 
    
    
    private function reward_single_left_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline, $referral_network_user,$k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count){
        //далее начинаем начисление наград участникам линии двигаясь в левую сторону перебирая массив участников 
        //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей суммы справа или слева,
        //если баланс имеется, то заново определяем баланс с каждой стороны, орпеделяем на какой стороне баланс меньше, начисляем награду от меньшей суммы
        $i = 0;
        $single_line_right_l = $single_line_right;
        $single_line_left_l = $single_line_left;
        array_unshift($single_line_right_l, $referral_network_user);//!!!!!!!добавляем рефовода в правую часть линии
        $control_all_summ = 0;
        $cash_all = [];
        while($i < count($single_line_left_l))
        {    
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_left_l);// получаем и одновременно убираем одного пользователя с левой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $current_cash = $user -> getCash();//текущие награды SinglLine  каждого юзера вызанного из массива
                $reward_wallet = $user -> getRewardWallet();//текущие доступные суммы для перевода на кошелек
                $user_id_pakege = $user -> getPakage();// стоимость пакета пользователя
                $limit_cash_back = $k_cash_back * $user_id_pakege;//лимит наисления дохода от СинглЛайн

                
                //получаем баланс левой и правой части линии
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left_l); $j++){
                    $single_line_left_balance_new[] = $single_line_left_l[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right_l); $k++){
                    $single_line_right_balance_new[] = $single_line_right_l[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_right_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды

                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
    
                        //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                        
                        if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            
                            $new_cash = $current_cash + $cash_user_bonus;
                            $user -> setCash($new_cash); 
                            $reward_user = $cash_user_bonus + $reward;
                            $reward_user_wallet = $cash_user_bonus + $reward_wallet;  
                            $user -> setReward($reward_user);
                            $user -> setRewardWallet($reward_user_wallet);
                            $cash_all[] = $cash_user_bonus;
                        }
                        
                        $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance_new < $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
    
                        //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                        
                        if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            
                            $new_cash = $current_cash + $cash_user_bonus;
                            $user -> setCash($new_cash); 
                            $reward_user = $cash_user_bonus + $reward;
                            $reward_user_wallet = $cash_user_bonus + $reward_wallet;  
                            $user -> setReward($reward_user);
                            $user -> setRewardWallet($reward_user_wallet);
                            $cash_all[] = $cash_user_bonus;
                        }
                    
                    $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance_new == $summ_single_line_right_balance_new){
                    $reward_user_new4 = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    //ничего не начисляем
                }
                array_unshift($single_line_right_l, $user);//добавляем  пользователя  в массив с правой стороны
                $control_all_summ += $cash_user_bonus;
        }
        return $cash_all;
    }

    private function makeMemberCode($arr1,$id,$arr2,$arr3){
        //$arr[0] = arr1 - id сети
        //$id пакета нового участника сети
        //$arr[2] = arr2 -id пакета владельца сети
        //$arr[3] = arr3  
        $member_code = $arr1.'-'.$id.'-'.$arr2.'-'.$arr3;
        return $member_code;
    }


    private function cashBackSummRight($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline, $accrual_limit){
        
        //расчет сумм и количества участников путем записи сумм начисления в массив для начислений по кешбэк в линии двигаемся в правую сторону линии относительно Рефовода
        $i = 0;
        $single_line_right_r = $single_line_right;//линия справа
        $single_line_left_r = $single_line_left;//линия слева
        $cash_all = [];//расчетная сумма начислений КешБек при использовании правила №1
        $control_all_summ = 0;
        while($i < count($single_line_right_r))
        {
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_right_r);// получаем и одновременно убираем одного пользователя с правой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $current_cash = $user -> getCash();//текущие награды SinglLine  каждого юзера вызанного из массива
                $reward_wallet = $user -> getRewardWallet();//текущие доступные суммы для перевода на кошелек
                $user_id_pakege = $user -> getPakage();// стоимость пакета пользователя
                $limit_cash_back = $k_cash_back * $user_id_pakege;//лимит наисления дохода от СинглЛайн

                for($j = 0; $j < count($single_line_left_r); $j++){
                    $single_line_left_balance_new[] = $single_line_left_r[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);

                $single_line_right_balance_new = [];//новый бланас с учетом изменения количества участников в линии при движении по линии в право
                for($k = 0; $k < count($single_line_right_r); $k++){
                    $single_line_right_balance_new[] = $single_line_right_r[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                //сравниваем новый баланс слева и справа относительно того участника к которому пришли в линии и для которого расчитываем начисление КешБек
                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_right_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды(это меньшая сторона)
                    //dd();
                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
    
                        //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                        
                        if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            
                            $cash_all[] = $cash_user_bonus;
                        }
                }
                elseif($summ_single_line_left_balance < $summ_single_line_right_balance){
                    $cash_user_single_control = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды

                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
    
                        //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                        
                        if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            $cash_all[] = $cash_user_bonus;
                        }       
                } 
                // elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                //     //если баланс окажется равный то участника также не включем в начисления
                //     $reward_user_new2 = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды   
                // } 
                array_unshift($single_line_left_r, $user);//добавляем  пользователя  в массив с левой стороны
                $control_all_summ += $cash_user_bonus;
        }
        return $cash_all;
    }
    
    private function cashBackSummLeft($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back, $payments_singleline,$accrual_limit){
         //расчет сумм и количества участников путем записи сумм начисления в массив для начислений по кешбэк в линии, двигаемся в левую сторону линии относительно Рефовода (в сторону меньшего баланса)
        $i = 0;
        $single_line_right_l = $single_line_right;//линия справа
        $single_line_left_l = $single_line_left;//линия слева
        $cash_all = [];//расчетная сумма начислений КешБек при использовании правила №1
        $control_all_summ = 0;
        while($i < count($single_line_left_l))
        {    
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_left_l);// получаем и одновременно убираем одного пользователя с левой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $current_cash = $user -> getCash();//текущие награды SinglLine  каждого юзера вызанного из массива
                $reward_wallet = $user -> getRewardWallet();//текущие доступные суммы для перевода на кошелек
                $user_id_pakege = $user -> getPakage();// стоимость пакета пользователя
                $limit_cash_back = $k_cash_back * $user_id_pakege;//лимит наисления дохода от СинглЛайн
                //dd($current_cash);
                
                //получаем баланс левой и правой части линии
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left_l); $j++){
                    $single_line_left_balance_new[] = $single_line_left_l[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right_l); $k++){
                    $single_line_right_balance_new[] = $single_line_right_l[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                //сравниваем новый баланс слева и справа относительно того участника к которому пришли в линии и для которого расчитываем начисление КешБек
                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_right_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    //dd($cash_user_single_control);
                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                       // dd($current_cash);
                    }

                    if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            
                        $cash_all[] = $cash_user_bonus;
                    }
                }
                elseif($summ_single_line_left_balance_new < $summ_single_line_right_balance_new){
                    $cash_user_single_control = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                    if($limit_cash_back - $current_cash >=  $cash_user_single_control){
                        $cash_user_bonus = $cash_user_single_control;
                    }
                    else{
                        $cash_user_bonus = $limit_cash_back - $current_cash;
                    }
                    if($current_cash < $limit_cash_back && $control_all_summ < $accrual_limit){
                            
                        $cash_all[] = $cash_user_bonus;
                    }
                }
                // elseif($summ_single_line_left_balance_new == $summ_single_line_right_balance_new){
                //      //если баланс окажется равный то участника также не включем в начисления
                //     $reward_user_new4 = ($summ_single_line_left_balance_new * $payments_singleline) / 100;//контрольная сумма баланса правой части линии по которой начисляются награды
                // }
                array_unshift($single_line_right_l, $user);//добавляем  пользователя  в массив с правой стороны
                $control_all_summ += $cash_user_bonus;
        }
        return $cash_all;
    }


    private function reward_cash_back_limit($single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$payments_cash_back_all_summ){
        $entityManager = $doctrine->getManager();
        array_pop($single_line); //убираем последнего участника (с правой стороны линии) сети которому не причитается выплата
        array_shift($single_line);//вырезаем первого участника массива (который имеет крайнюю позицию в линии слева) которому не причитается выплата 
        $cash_all = [];
        $reward_user_new1 = $payments_cash_back_all_summ;//контрольная сумма  для начисления награды кешбэк

        foreach($single_line as $user){
            $cash_refovod = $user -> getCash();
            $user_id_pakege = $user -> getPakage();
            $limit_cash_back = $k_cash_back * $user_id_pakege;
            $reward1 = $user -> getReward();
            if($reward1 <= $limit_cash_back){
                $new_cash = $cash_refovod + $reward_user_new1;
                $user -> setCash($new_cash); 
                $reward_user1 = $reward_user_new1 + $reward1; 
                $user -> setReward($reward_user1);
                $cash_all[] = $reward_user_new1;
                $entityManager->flush();
            }   
        }   
        return $cash_all;
    } 

    
    private function cycleRule($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$referral_network_all, $k_payments_direct,$accrual_limit,$cash_back_all_left_count,$cash_back_all_right_count,$payments_singleline){
       //расчет сумм и количества участников путем записи сумм начисления в массив для начислений по кешбэк в линии, двигаемся в левую сторону линии относительно Рефовода (в сторону меньшего баланса)
       $i = 5;
       $entityManager = $doctrine->getManager();
       //правилу циклов начисление проводится :
       //начисление рефоводу 10% КешБек 
       //начисление организаторам 10% на всех организаторов
       //начиление 4-м участникам с конца линии со стороны меньшего баланса не считая крайнего участника по 10% каждому
       //если в хвосте линии меньше 4-х участников до КешБек доначисляется участникам с противоположной стороны от рефовода в сторону большего баланса

        //начисление КешБек организаторам линии
        $referral_network_all;
        $single_line_organizer_new = [];
        $k_cash_organizer = $payments_singleline / $i;
        if($summ_single_line_left_balance > $summ_single_line_right_balance){
            $cash_organizer = ($summ_single_line_right_balance * $k_cash_organizer) /100;
        }
        elseif($summ_single_line_left_balance < $summ_single_line_right_balance){
            $cash_organizer = ($summ_single_line_left_balance * $k_cash_organizer) / 100;
        }
        //dd($k_cash_back);   
            for($j = 0; $j < $i; $j++){
                $single_line_organizer_new[] = $cash_organizer;
                $current_organizer_reward = $referral_network_all[$j] -> getReward();
                $current_organizer_cash = $referral_network_all[$j] -> getCash();
                $current_organizer_rewardwallet = $referral_network_all[$j] -> getrewardWallet();
                $user_organizer_cash_new = $cash_organizer + $current_organizer_cash;
                $user_organizer_reward_new = $cash_organizer + $current_organizer_reward;
                $user_organizer_rewardwallet_new = $current_organizer_rewardwallet + $cash_organizer;
                $referral_network_all[$j] -> setCash($user_organizer_cash_new);
                $referral_network_all[$j] -> setReward($user_organizer_reward_new);
                $referral_network_all[$j] -> setRewardWallet($user_organizer_rewardwallet_new);
                $entityManager->flush();
            } 
        $summ_single_line_organizer_new = array_sum($single_line_organizer_new); 
        
        
       //начисление КешБек  участникам линии 
       $single_line_right_l = $single_line_right;//линия справа
       $single_line_left_l = $single_line_left;//линия слева
       $cash_all_cycle = [];//расчетная сумма начислений КешБек при использовании правила №1
       

       if($summ_single_line_left_balance > $summ_single_line_right_balance){  
                $cash_user_single_control = ($summ_single_line_right_balance * $payments_singleline) / 100;//размер КешБек
                $accrual_limit_cycle = $accrual_limit - $cash_user_single_control;// вычитаем из доли к начислению СинглЛайн выплаты организаторов
                $single_line_right_l_reverse = array_reverse($single_line_right_l);//переворачиваем массив чтобы сначала брать  последние записи
                $single_line_right_balance_new = [];
                $single_line_left_balance_new =[];
                $control_all_summ = 0;
                //начисление начинаем со второго участника так как первый участник в массиве это последний учстник линии которому не начиляются бонусы
                //начисления производятся только те участникам у которых не превышен лимит 300% начисления СинглЛайн
                for($j = 1; $j< count($single_line_right_l_reverse); $j++){
                    //dd($single_line_right_l_reverse);
                    $current_user_reward = $single_line_right_l_reverse[$j] -> getReward();
                    $current_user_cash = $single_line_right_l_reverse[$j] -> getCash();
                    $current_user_rewardwallet = $single_line_right_l_reverse[$j] -> getRewardWallet();
                    $pakege = $single_line_right_l_reverse[$j] -> getPakage();
                    $cash_user_single_limit = $pakege * $k_cash_back;//условие начисления КешБек проверка лимита начисления
                    
                    if($cash_user_single_limit > $current_user_cash && $control_all_summ < $accrual_limit_cycle){
                        //dd('привет');
                        // if($accrual_limit - $current_user_cash >=  $cash_user_single_control){
                        //     $cash_user_single = $cash_user_single_control;
                        // }
                        // else{
                        //     $cash_user_single = $accrual_limit - $current_user_cash;
                        // }
                        
                        if($cash_user_single_limit - $current_user_cash >= $cash_user_single_control){
                            $cash_user_single = $cash_user_single_control;
                            $user_cash_new = $cash_user_single + $current_user_cash;
                            $user_reward_new = $cash_user_single + $current_user_reward;
                            $user_rewardwallet_new = $cash_user_single + $current_user_rewardwallet;
                            $single_line_right_l_reverse[$j] -> setCash($user_cash_new);
                            $single_line_right_balance_new[] = $cash_user_single;
                            $single_line_right_l_reverse[$j] -> setReward($user_reward_new);
                            $single_line_right_l_reverse[$j] -> setRewardwallet($user_rewardwallet_new);
                            $control_all_summ += $cash_user_single;
                        }
                        else{
                            $user_cash_singleline_new = $cash_user_single_limit - $current_user_cash;
                            $user_rewardwallet_new = $user_cash_singleline_new + $current_user_rewardwallet;
                            $user_cash_new = $user_cash_singleline_new + $current_user_cash;
                            $user_reward_new = $user_cash_singleline_new + $current_user_reward;
                            $single_line_right_l_reverse[$j] -> setCash($user_cash_new);
                            $single_line_right_balance_new[] = $user_cash_singleline_new;
                            $single_line_right_l_reverse[$j] -> setReward($user_reward_new);
                            $single_line_right_l_reverse[$j] -> setRewardwallet($user_rewardwallet_new);
                            $control_all_summ += $user_cash_singleline_new;
                        }
                        $entityManager->flush();    
                    }    
                }
                //dd($accrual_limit);
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);
                
                
                if($control_all_summ < $accrual_limit){
                    //$single_line_left_l_reverse = array_reverse($single_line_left_l);//переворачиваем массив чтобы сначала брать  последние записи
                    for($j = 0; $j< count($single_line_left_l) - 1; $j++){
                        $current_user_reward = $single_line_left_l[$j] -> getReward();
                        $current_user_cash = $single_line_left_l[$j] -> getCash();
                        $current_user_rewardwallet = $single_line_left_l[$j] -> getRewardWallet();
                        $pakege = $single_line_left_l[$j] -> getPakage();
                        $cash_user_single_limit = $pakege * $k_cash_back;//условие начисления КешБек проверка лимита начисления
                        if($cash_user_single_limit > $current_user_cash &&  $control_all_summ < $accrual_limit_cycle){
                            // if($accrual_limit - $current_user_cash >=  $cash_user_single_control){
                            //     $cash_user_single = $cash_user_single_control;
                            // }
                            // else{
                            //     $cash_user_single = $accrual_limit - $current_user_cash;
                            // }

                            if($cash_user_single_limit - $current_user_cash >= $cash_user_single_control){
                                $cash_user_single = $cash_user_single_control;
                                $user_cash_new = $cash_user_single + $current_user_cash;
                                $user_reward_new = $cash_user_single + $current_user_reward;
                                $user_rewardwallet_new = $cash_user_single + $current_user_rewardwallet;
                                $single_line_left_l[$j] -> setCash($user_cash_new);
                                $single_line_left_balance_new[] = $cash_user_single;
                                $single_line_left_l[$j] -> setReward($user_reward_new);
                                $single_line_left_l[$j] -> setRewardwallet($user_rewardwallet_new);
                                $control_all_summ += $cash_user_single;
                            }
                            else{
                                $user_cash_singleline_new = $cash_user_single_limit - $current_user_cash;
                                $user_cash_new = $user_cash_singleline_new + $current_user_cash;
                                $user_reward_new = $user_cash_singleline_new + $current_user_reward;
                                $user_rewardwallet_new = $user_cash_singleline_new + $current_user_rewardwallet;
                                $single_line_left_l[$j] -> setCash($user_cash_new);
                                $single_line_left_balance_new[] = $user_cash_singleline_new;
                                $single_line_left_l[$j] -> setReward($user_reward_new);
                                $single_line_left_l[$j] -> setRewardwallet($user_rewardwallet_new);
                                
                                $control_all_summ += $user_cash_singleline_new;
                            }
                            $entityManager->flush();
                        }
                    }
                    
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);   
       }
       elseif($summ_single_line_left_balance < $summ_single_line_right_balance){
        
                $cash_user_single_control = ($summ_single_line_left_balance * $payments_singleline) / 100;//размер КешБек
                $accrual_limit_cycle = $accrual_limit - $cash_user_single_control;
                $single_line_left_l_reverse = array_reverse($single_line_left_l);//переворачиваем массив чтобы сначала брать  последние записи
                $single_line_right_balance_new = [];
                $single_line_left_balance_new =[];
                $control_all_summ = 0;
                //начисление начинаем со второго участника так как первый участник в массиве это последний учстник линии которому не начиляются бонусы
                for($j = 1; $j< count($single_line_left_l_reverse); $j++){
                    
                    
                        $current_user_reward = $single_line_left_l_reverse[$j] -> getReward();
                        $current_user_rewardwallet = $single_line_left_l_reverse[$j] -> getRewardWallet();
                        $current_user_cash = $single_line_left_l_reverse[$j] -> getCash();
                        $pakege = $single_line_left_l_reverse[$j] -> getPakage();
                        $cash_user_single_limit = $pakege * $k_cash_back;//условие начисления КешБек проверка лимита начисления
                        if($cash_user_single_limit > $current_user_cash && $control_all_summ < $accrual_limit_cycle){
                            // if($accrual_limit - $current_user_cash >=  $cash_user_single_control){
                            //     $cash_user_single = $cash_user_single_control;
                            // }
                            // else{
                            //     $cash_user_single = $accrual_limit - $current_user_cash;
                            // }

                            if($cash_user_single_limit - $current_user_cash >= $cash_user_single_control){
                                $cash_user_single = $cash_user_single_control;
                                $user_cash_new = $cash_user_single + $current_user_cash;
                                $user_reward_new = $cash_user_single + $current_user_reward;
                                $user_rewardwallet_new = $cash_user_single + $current_user_rewardwallet;
                                $single_line_left_l_reverse[$j] -> setCash($user_cash_new);
                                $single_line_left_l_reverse[$j] -> setReward($user_reward_new);
                                $single_line_left_balance_new[] = $cash_user_single;
                                $single_line_left_l_reverse[$j] -> setRewardWallet($user_rewardwallet_new);
                                $control_all_summ += $cash_user_single;
                            }
                            else{
                                $user_cash_singleline_new = $cash_user_single_limit - $current_user_cash;
                                $user_cash_new = $user_cash_singleline_new + $current_user_cash;
                                $user_reward_new = $user_cash_singleline_new + $current_user_reward;
                                $user_rewardwallet_new = $user_cash_singleline_new + $current_user_rewardwallet;
                                $single_line_left_l_reverse[$j] -> setCash($user_cash_new);
                                $single_line_left_l_reverse[$j] -> setReward($user_reward_new);
                                $single_line_left_balance_new[] = $user_cash_singleline_new;
                                $single_line_left_l_reverse[$j] -> setRewardWallet($user_rewardwallet_new);
                                $control_all_summ += $user_cash_singleline_new;
                            }
                        }
                    $entityManager->flush();
                    
                } 
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new); 

                if($control_all_summ < $accrual_limit){
                    //$single_line_right_l_reverse = array_reverse($single_line_right_l);//переворачиваем массив чтобы сначала брать  последние записи
                    for($j = 0; $j< count($single_line_right_l) - 1; $j++){
                        $current_user_reward = $single_line_right_l[$j] -> getReward();
                        $current_user_cash = $single_line_right_l[$j] -> getCash();
                        $current_user_rewardwallet = $single_line_right_l[$j] -> getRewardWallet();
                        $pakege = $single_line_right_l[$j] -> getPakage();
                        $cash_user_single_limit = $pakege * $k_cash_back;//условие начисления КешБек проверка лимита начисления
                        if($cash_user_single_limit > $current_user_reward && $control_all_summ < $accrual_limit_cycle){
                            // if($accrual_limit - $control_all_summ >=  $cash_user_single_control){
                            //     $cash_user_single = $cash_user_single_control;
                            // }
                            // else{
                            //     $cash_user_single = $accrual_limit - $current_user_cash;
                            // }

                            if($cash_user_single_limit - $current_user_cash >= $cash_user_single_control){
                                $cash_user_single = $cash_user_single_control;
                                $user_cash_new = $cash_user_single + $current_user_cash;
                                $user_reward_new = $cash_user_single + $current_user_reward;
                                $user_rewardwallet_new = $cash_user_single + $current_user_rewardwallet;
                                $single_line_right_l[$j] -> setCash($user_cash_new);
                                $single_line_right_balance_new[] = $cash_user_single;
                                $single_line_right_l[$j] -> setReward($user_reward_new);
                                $single_line_right_l[$j] -> setRewardWallet($user_rewardwallet_new);
                                $control_all_summ += $cash_user_single;
                            }
                            else{
                                $user_cash_singleline_new = $cash_user_single_limit - $current_user_cash;
                                $user_cash_new = $user_cash_singleline_new + $current_user_cash;
                                $user_reward_new = $user_cash_singleline_new + $current_user_reward;
                                $user_rewardwallet_new = $user_cash_singleline_new + $current_user_rewardwallet;
                                $single_line_right_l[$j] -> setCash($user_cash_new);
                                $single_line_right_balance_new[] = $user_cash_singleline_new;
                                $single_line_right_l[$j] -> setReward($user_reward_new);
                                $single_line_right_l[$j] -> setRewardWallet($user_rewardwallet_new);
                                $control_all_summ += $user_cash_singleline_new;
                            }
                        }
                    $entityManager->flush();
                    }
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);      
       }
       $summ_single_line_balance_new = $summ_single_line_right_balance_new + $summ_single_line_left_balance_new + $summ_single_line_organizer_new;//общая сумма начислений КешБек
       return $summ_single_line_balance_new;
   }
}