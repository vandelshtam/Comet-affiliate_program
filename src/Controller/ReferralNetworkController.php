<?php

namespace App\Controller;

use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Form\ReferralNetworkType;
use App\Entity\ListReferralNetworks;
use App\Entity\SettingOptions;
use App\Entity\TokenRate;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/referral/network')]
class ReferralNetworkController extends AbstractController
{
    #[Route('/', name: 'app_referral_network_index', methods: ['GET'])]
    public function index(ReferralNetworkRepository $referralNetworkRepository,ManagerRegistry $doctrine): Response
    {         
        return $this->render('referral_network/index.html.twig', [
            'referral_networks' => $referralNetworkRepository->findAll(),
        ]);
    }


    #[Route('/{my_team}/myteam', name: 'app_referral_network_myteam', methods: ['GET'])]
    public function myTeam(ReferralNetworkRepository $referralNetworkRepository,ManagerRegistry $doctrine, string $my_team): Response
    { 
        $entityManager = $doctrine->getManager(); 
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь)       
        return $this->render('referral_network/index.html.twig', [
            'referral_networks' => $array_my_team,
        ]);
    }

    #[Route('/{referral_link}/{id}/new', name: 'app_referral_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, string $referral_link, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['network_code' => $referral_link]);
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $arr = explode('-', $referral_link);//уникальный персональный код участника сети со статусом владелец сети преобразуем в массив для извлечения информации об участнике предоставившегго реферальную ссылку (рефовод)
    
        //создание уникального персонального кода  нового участника сети пришедшего по реферальной ссылке (рефовода)
        $arr1 = $arr[0]; $arr2 = $arr[2]; $arr3 = $arr[3];
        $member_code = $this -> makeMemberCode($arr1,$id,$arr2,$arr3);

        $referralNetwork = new ReferralNetwork();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        $user = $this -> getUser();
        $username = $user -> getUsername();
        if ($form->isSubmitted() && $form->isValid()) {
            //проводим предварительное создание записи в таблицу строки нового участника реферальной сети 
            $referralNetworkRepository->add($referralNetwork);
            //запись нового участника в линию single_line
            $this -> newConfirm($request,$referralNetworkRepository, $doctrine,$member_code,$id,$referral_link);
            $this->addFlash(
                         'success',
                         'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
                        
            return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('referral_network/new.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
            'username' => $username,
            'member_code' => $member_code,
        ]);
    }

    #[Route('/myplace', name: 'app_referral_network_myplace', methods: ['GET', 'POST'])]
    public function my_place(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine)
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        if ($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $user_id]) == true) {
            $id = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $user_id]) -> getId();
            return $this->redirectToRoute('app_referral_network_show', ['id' => $id], Response::HTTP_SEE_OTHER);
        }
        else{
            $this->addFlash(
                'danger',
                'Такого пользователя нет.');
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }
    }


    #[Route('/{id}', name: 'app_referral_network_show', methods: ['GET'])]
    public function show(ReferralNetwork $referralNetwork,ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine,  int $id,): Response
    {

        $entityManager = $doctrine->getManager();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['id' => $id]);
        $my_team = $referral_network -> getMyTeam();
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь)
        
        $user_status = $referral_network -> getUserStatus();
        $my_team_count = count($array_my_team);
        foreach($array_my_team as $array){
            $array_my_team_summ_pakege[] = $array -> getPakage();
        }
        $my_team_summ = array_sum($array_my_team_summ_pakege);
        
        $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);//объект владельца сети
        $user_id = $referral_network -> getUserId();
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id' => $user_id]);// получаем оъект пакета  участника реферальной сети
        $owner_array[] = $user_owner;//основатель сети
        $reward = $referral_network -> getReward();
        $k_cash_back = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getCashBack()/100;//получаем коэффициент начисления cash_back

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

        return $this->render('referral_network/show.html.twig', [
            'referral_network' => $referralNetwork,
            'data' => $array_data,
            'pakege_user' => $pakege_user,
            'k_cash_back' => $k_cash_back,
            'my_team_count' => $my_team_count,
            'my_team_summ' => $my_team_summ,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_referral_network_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/edit.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_referral_network_delete', methods: ['POST'])]
    public function delete(Request $request, ReferralNetwork $referralNetwork, ReferralNetworkRepository $referralNetworkRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$referralNetwork->getId(), $request->request->get('_token'))) {
            $referralNetworkRepository->remove($referralNetwork);
        }

        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
    }

    private function newConfirm($request,  $referralNetworkRepository,  $doctrine,  $member_code,  $id,  $referral_link)
    {
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();//получем две самых новых по времени записи в реферальной сети (в таблице), предпоследняя запись содержит статус пользователя (left/right) для присвоения статуса новому пользователю
        $status_user = $referral_network_status[1]->getUserStatus();//получаем запись самого нового участника сети ,определяем его положение в линии "слева" или "справа"
        $status = $this -> status($status_user);// присваеваем новому участнику сети положение в линии  слева или справа
        $referral_network_referral = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//получаем объект пользователя участника  реферальной сети который предоставил реферальную ссылку
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем данные нового участника в реферальной сети (рефовод) чтобы дополнить информацию всеми необходимыми данными
        $network_code = $referral_network_referral -> getNetworkCode();//получаем код родительской сети
        $list_network = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['network_code' => $network_code]);//обект родительской сети
        $list_network_all = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField([$network_code]);//все обекты родительской сети
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета нового участника реферальной сети
        $arr = explode('-', $member_code);//уникальный код участника сети преобразуем в массив для получения важных данных
        $listReferralNetwork_id = $arr[0];//айди реферальной сети
        $pakege_user_id = $arr[1];//айди пакета нового участника сети
        $user_id = $pakege_user -> getUserId();//айди нвого участника сети
        $balance = $pakege_user -> getPrice();//стоимость пакета нового участника сети
        $list_network_all_count = count($list_network_all);
        
	    //получаем объект записи родительской реферальной сети и получем из нее код этой сети
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();

        //изменения статуса пакета приглашенного участника сети на "активирован"
        $pakege_user -> setActivation('активирован');
        $pakege_user -> setReferralNetworksId($network_code);

        //построение первичной линии при количестве учстников менее 3-х
        //данные пользователя который предоставил реферальную ссылку
        $network_referral_id = $referral_network_referral -> getId();//айди записи участника реферальной сети предоставившего ссылку (рефовода)
        $user_referral_id = $referral_network_referral -> getUserId();//пользователя системы участвующего в реферальной сети и предоставившего реферальную ссылку

        //расчет награды за приглашенного участника члену сети предоставишему реферальную ссылку (рефовода) DIRECT
        $bonus = $balance * 0.1;//direct начисление за приглашенного участника
        //dd($bonus);
        $referral_network_referral_bonus = $referral_network_referral -> getReward();
        $referral_network_referral_direct = $referral_network_referral -> getDirect();
        $reward = $bonus + $referral_network_referral_bonus;
        $direct = $bonus + $referral_network_referral_direct;
        $profit_network_advance = $balance - $bonus;
        //dd($direct);
        $referral_network_referral -> setReward($reward);
        $referral_network_referral -> setDirect($direct);
        
        //записываем и сохраняем в таблицу участника реферальной сети все дополнительные и обязательные данные
        $user = $this -> getUser();    
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus($status);
        $referral_network -> setPakegeId($pakege_user_id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setBalance($balance);
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setUserReferralId($user_referral_id);
        $referral_network -> setNetworkReferralId($network_referral_id);
        $referral_network -> setMyTeam($referral_link);
        $referral_network -> setPaymentsNetwork($bonus);
        $referral_network -> setPaymentsCash(0);
        $referral_network -> setCurrentNetworkProfit(0);
        $referral_network -> setPakage($balance);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referralNetworkRepository->add($referral_network);
        $old_profit_network = $list_network -> getProfitNetwork();
        $new_profit_network = $old_profit_network + $profit_network_advance;
        //$list_network ->setProfitNetwork($new_profit_network);


        //========выполнение формулы одной линии========= 
        $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'right']);
        $referral_network_user_id = $referral_network_referral -> getId();

    //dd($list_network_all_count);
        //первое построение линии из трех участников реферальной сети
        if($list_network_all_count == 2){
            $referral_network_user = $referral_network_referral;
            $this -> singleThree($referral_network_left,$referral_network_right,$referral_network_user,$old_profit_network,$list_network,$referral_network,$bonus);
            $entityManager->persist($referral_network);
            $entityManager->persist($referral_network_referral);
            $entityManager->flush();
        }

        //построение линии при количестве участников более 3-х
        if($list_network_all_count > 2){
            $referral_network_user = $referral_network_referral;
            $referral_network_user_new = $referral_network;
            $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
            $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);
            $this -> single($request, $referralNetworkRepository, $referral_network_count, $user_owner, $doctrine,$referral_network_user, $referral_network_user_new, $referral_network, $member_code, $id, $referral_link,$profit_network_advance,$list_network,$network_code,$bonus);
            $entityManager->persist($referral_network);
            $entityManager->flush();
        }

        //получаем все начисления и погашеня сети 
        $list_network_all_new = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField([$network_code]);//обновляем все обекты родительской сети
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
        $curren_network_summ = array_sum($curren_network);
        $payments_direct_summ = array_sum($payments_direct);
        $payments_cash_summ = array_sum($payments_cash);
        $current_price_summ = array_sum($current_price);

        //запись данных начислений во всей сети в родительский объект сети
        $listReferralNetwork -> setProfitNetwork($curren_network_summ);
        $listReferralNetwork -> setPaymentsDirect($payments_direct_summ);
        $listReferralNetwork -> setPaymentsCash($payments_cash_summ);
        $listReferralNetwork -> setCurrentBalance($current_price_summ);

        $entityManager->persist($referral_network);
        $entityManager->persist($referral_network_referral);
        $entityManager->flush();    
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


    public function single($request, ReferralNetworkRepository $referralNetworkRepository, $referral_network_count, $user_owner, $doctrine,$referral_network_user,$referral_network_user_new,$referral_network, $member_code, $id, $referral_link,$profit_network_advance,$list_network,$network_code,$bonus)
    {
        //=========формула расчета наград в сети при количестве участников 4 и более======
        //получаем информацию о сети и записи участника предоставившего реферальную ссылку(рефовода)
        $entityManager = $doctrine->getManager();
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        //$referral_network_left_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('left',0);//получаем объект участников с левой стороны линии с балансом более "0"
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        //$referral_network_right_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('right',0);//получаем объект участников с правой стороны линии с балансом более "0"
        $referral_network_all = $entityManager->getRepository(ReferralNetwork::class)->findByMemberField(['network_code' => $network_code]);//получаем все  объекты сети
        //$referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByStatusField(['right',$network_code]);//получаем объект участников участников с правой стороны
        //$referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByStatusField(['left',$network_code]);//получаем объект всех участников с левой стороны линии 
        $pakege_all = $entityManager->getRepository(Pakege::class)->findByExampleField(['referral_networks_id' => $network_code]);//получаем все  объекты купленных в сети пакетов
        $price_pakage_all = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getAllPricePakage();//получаем параметр предельного баланса всех купленных пакетов для начисления выплат, если сеть достигла предела выплаты single-line не начисляются
        $k_payments_singl_line = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getPaymentsSingleline();//получаем коеффициент начисления наград в  single-line 
        $k_payments_direct = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getPaymentsDirect();//получаем коэффициент начисления direct
        $k_cash_back = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getCashBack()/100;//получаем коэффициент начисления cash_back
        $k_accrual_limit = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]) -> getAccrualLimit()/100;//получаем коэффициент общей суммы  предела начислений в линии в виде cash-back
        $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();//получаем курс внутреннего токена сети
        $user_referral_status = $referral_network_user -> getUserStatus();
        $owner_array[] = $user_owner;//основатель сети

        //сумма стоимости всех приобретенных в сети пакетов
        foreach($pakege_all as $pakages){
            $pakages_summ[] = $pakages -> getPrice();
        }
        $all_pakages_summ = array_sum($pakages_summ) * $token_rate;//фактическая сумма стоимости приобретенных пакетов в сети переведенная в лакальный токен по курсу


        //построение линии сингл-лайт
        $single_line = array_merge($referral_network_left, $owner_array, $referral_network_right);//объеденяем в один массив в  соотвтетсвии с правилом построения линии сингл-лайн
        for($i = 0; $i <= count($single_line); $i++){
            if($single_line[$i] -> getMemberCode() == $referral_link){
                $key_user = $i;
                $single_line_id_refovod = $i;
                break;
            }
        }
        $single_line_left = [];
        for($i = 0; $i < $key_user; $i++){
            $single_line_left[] = $single_line[$i];
        }
        $single_line_right = [];
        for($i = $key_user + 1; $i < count($single_line); $i++){
            $single_line_right[] = $single_line[$i];
        }
        $array_single_line_right = $single_line_right;
        $array_single_line_left = $single_line_left;
        //dd($array_single_line_left);
        
        //получаем баланс левой и правой части линии перед погашением
        $single_line_left_balance = [];
        for($i = 0; $i < count($single_line_left); $i++){
            $single_line_left_balance[] = $single_line_left[$i] -> getBalance();
        }
        $summ_single_line_left_balance = array_sum($single_line_left_balance);
        
        $single_line_right_balance = [];
        for($i = 0; $i < count($single_line_right); $i++){
            $single_line_right_balance[] = $single_line_right[$i] -> getBalance();
        }
        $summ_single_line_right_balance = array_sum($single_line_right_balance);
    
        // //==============проводим погашение баланса покетов пользователей в линии=============
        //     //сформируем массивы баланса пакетов больше нуля с левой и с правой стороны
        // if($summ_single_line_left_balance != $summ_single_line_right_balance && ($summ_single_line_left_balance != 0 or $summ_single_line_right_balance != 0)){
        //     $single_line_left_balance_pakege = [];
        //     for($i = 0; $i < count($array_single_line_left); $i++){
        //         if($array_single_line_left[$i] -> getBalance() > 0){
        //             $single_line_left_balance_pakege[] = $array_single_line_left[$i];
        //             $array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
        //         }    
        //     }
        //     //dd($array_single_line_left);
        //     $summ_left_balance_pakege = array_sum($array_left_balance_pakege);
        //     $count_left_balance_pakege = count($array_left_balance_pakege);
            
        //     $single_line_right_balance_pakege = [];
        //     for($i = 0; $i < count($array_single_line_right); $i++){
        //         if($array_single_line_right[$i] -> getBalance() > 0){
        //             $single_line_right_balance_pakege[] = $array_single_line_right[$i];
        //             $array_right_balance_pakege[] = $array_single_line_right[$i] -> getBalance();
        //         }
        //     }
        //     $summ_right_balance_pakege = array_sum($array_right_balance_pakege);
        //     $count_right_balance_pakege = count($array_right_balance_pakege);
            
        //     //запись нвого баланса стоимости пакетов
        //     if($summ_left_balance_pakege > $summ_right_balance_pakege){
        //         $summ_remainder = $summ_left_balance_pakege - $summ_right_balance_pakege;

        //         for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
        //             $balance_old1 = $single_line_left_balance_pakege[$i] -> getBalance();
        //             $participation_rate = $balance_old1 / $summ_left_balance_pakege;
        //             $single_line_left_balance_pakege[$i] -> setKoef($participation_rate);
        //             $entityManager->flush();
        //         }
        //         for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
        //             $participation_rate_user = $single_line_left_balance_pakege[$i] -> getKoef();
        //             $new_balance_user = $participation_rate_user * $summ_remainder;
        //             $single_line_left_balance_pakege[$i] -> setBalance($new_balance_user);
        //             $entityManager->flush();
        //         }

        //         for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
        //             $single_line_right_balance_pakege[$i] -> setBalance(0);
        //             $entityManager->flush();
        //         }
                
        //     }
        //     else{
        //         $summ_remainder2 = $summ_right_balance_pakege - $summ_left_balance_pakege;
        //         for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                    
        //             $balance_old2 = $single_line_right_balance_pakege[$i] -> getBalance();
        //             $participation_rate2 = $balance_old2 / $summ_right_balance_pakege;
        //             $single_line_right_balance_pakege[$i] -> setKoef($participation_rate2);
        //             $entityManager->flush();
        //         }
        //         for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                    
        //             $participation_rate_user2 = $single_line_right_balance_pakege[$i] -> getKoef();
        //             $new_balance_user2 = $participation_rate_user2 * $summ_remainder2;
        //             $single_line_right_balance_pakege[$i] -> setBalance($new_balance_user2);
        //             $entityManager->flush();
        //         }

        //         for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
        //             $single_line_left_balance_pakege[$i] -> setBalance(0);
        //             $entityManager->flush();
        //         }
        //     } 
        // }
            
            
        // //вычисление и запись вознаграждений rewards
        // //получаем баланс левой и правой части линии после погашения
        // $single_line_left_balance = [];
        // for($i = 0; $i < count($single_line_left); $i++){
        //     $single_line_left_balance[] = $single_line_left[$i] -> getBalance();
        // }
        // $summ_single_line_left_balance = array_sum($single_line_left_balance);
        
        // $single_line_right_balance = [];
        // for($i = 0; $i < count($single_line_right); $i++){
        //     $single_line_right_balance[] = $single_line_right[$i] -> getBalance();
        // }
        // $summ_single_line_right_balance = array_sum($single_line_right_balance);
        
     
        //определяем с какой стороны линии сумма баланса больше
        if($summ_single_line_left_balance == 0 || $summ_single_line_right_balance == 0){
            //dd('malance 0');
            $not_needed_variable = 0;
            //сообщение о достижении предела общего баланса сети
            if($price_pakage_all <= $all_pakages_summ){
                $this->addFlash(
                    'danger',
                    'Сеть достигла предела накопления пакетов 1');
            }
        }
        elseif($summ_single_line_left_balance == $summ_single_line_right_balance ){
            //dd(',fkfyc 000');
                //вычислим и запишем награду участнику относительно которого выстроена линия (рефоводу) и проведем погашение балансов пакетов
                $this -> where_is_balance($referral_network_user,$summ_single_line_right_balance);
                $entityManager->flush();
                $repayment_amount = 0;
                //всем кроме рефовода обнуляем баланс
                while($i <= count($single_line_right)){
                    $user = array_shift($single_line_right);
                    $user -> setBalance(0);
                    $entityManager->flush(); 
                } 
                while($i <= count($single_line_left)){
                    $user = array_shift($single_line_left);
                    $user -> setBalance(0);
                    $entityManager->flush(); 
                    } 
                $repayment_amount = ($summ_single_line_left_balance + $summ_single_line_right_balance) - $bonus;
                //информационное сообщение о достижении предела общей стоимости сети (купленных пакетов)
                if($price_pakage_all <= $all_pakages_summ){
                    $this->addFlash(
                        'danger',
                        'Сеть достигла предела накопления пакетов 2');
                }  
        }
        elseif($summ_single_line_left_balance != $summ_single_line_right_balance ){
            
                    //dd('balance no 0');
                    //array_unshift($single_line_right, $referral_network_user);//добавляем рефовода который предоставил ссылку в массив с права 
                    $count_left = count($single_line_left);
                    $count_right = count($single_line_right);

                    //высчитаем сумму отведенную в проекте для начисления в линии Синг-Лайн
                    if($summ_single_line_left_balance > $summ_single_line_right_balance){
                        $summ_ammoutn_all = $summ_single_line_right_balance * 2;
                    }
                    else{
                        $summ_ammoutn_all = $summ_single_line_left_balance * 2;
                    }
                    $accrual_limit = $summ_ammoutn_all * $k_accrual_limit;

                    //получение общей суммы начислений в линии в виде cash-back

                 
                    //==========вычисляем и записываем награды участникам лини двигаясь в левую сторону ===========
                    array_unshift($single_line_right, $referral_network_user);//добавляем рефовода который предоставил ссылку в массив с права , так как сначала масивы участников строились слева и спава от рефовода, самого рефовода
                    //нет в массивах, теперь чтобы начислять награды двигаясь полинии и сравнивая балансы, рефовода нужно добаить в любой массив, в данном случае добавлен в массив справа
                    $count_left = count($single_line_left);
                    $count_right = count($single_line_right);
            //условие начисления выплат по линии Сингл-Лайн в зависимсоти от количества проданных пакетов
            if($price_pakage_all > $all_pakages_summ){ 
                //условие начисления выплат по линии Сингл-Лайн если общее количество выплат меньше норматива суммы (сейчас 70% от суммы погашения-зачисления к проект сети) 
                //то начисление производится в размере определенногокоэффициента (сейчас 10%), если общая сумма начислений к выплате превашает установленную сумму (70%) то 
                //сумма предназначенная для выплат делиться на всех участников , которые должны получить начисление

                //проверяем общее начисление и количество пользователей к начислению КешБэк в линии с помощью методов
                $cash_back_all_1 = $this -> cashBackSummLeft($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back);
                $cash_back_all_2 = $this -> cashBackSummRight($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back);
                $cash_back_all_1_summ = array_sum($cash_back_all_1); 
                $cash_back_all_2_summ = array_sum($cash_back_all_2);
                $cash_back_all_1_count = count($cash_back_all_1); 
                $cash_back_all_2_count = count($cash_back_all_2);
                $cash_back_all_summ = $cash_back_all_1_summ + $cash_back_all_2_summ;
                $cash_back_all_count = $cash_back_all_1_count + $cash_back_all_2_count;

                if($accrual_limit > $cash_back_all_summ){
                    //теперь проделываем операции по определению наград двигаясь в левую сторону от рефовода по линии 
                    $all_cash_right = $this -> reward_single_right_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine, $count_left, $count_right,$k_cash_back);  
                    //теперь проделываем операции по определению наград двигаясь в правую сторону от рефовода по линии 
                    $all_cash_left = $this -> reward_single_left_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back);
                    $this->addFlash(
                        'danger',
                        'Ограничений по начислению кешбек нет'); 
                }
                elseif($accrual_limit <= $cash_back_all_summ){
                    //сначала высчитаем конечную сумму выплаты по кешбэк каждому участнику 
                    $payments_cash_back_all_summ = $accrual_limit/$cash_back_all_count; //общую предельную сумму которую можно выплачивать в сеть разделим на количество учтсников которым она причитается
                    $all_cash_right = $this -> reward_cash_back_limit($single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$payments_cash_back_all_summ);
                    $all_cash_left = [0];
                    //проведем начисление кешбэк с помощью специального метода

                    $this->addFlash(
                        'danger',
                        'Внимание! Начисление проведено с ограничением по начислению кешбек, чтобы не привысить лимит начислений 70%'); 
                }
                    
            } 
            else{
                $this->addFlash(
                    'danger',
                    'ВНИМАНИЕ! Сеть достигла предела накопления пакетов 3');
            }       

                    //=====запись текущих начислений и выплат в сети ========
            //условие начисления выплат по линии Сингл-Лайн в зависимсоти от количества проданных пакетов
            if($price_pakage_all > $all_pakages_summ){   
                    $all_cash_right_summ = array_sum($all_cash_right);
                    $all_cash_left_summ = array_sum($all_cash_left);
            }
            else{
                    $all_cash_right_summ = 0;
                    $all_cash_left_summ = 0;
            }
                    $payments_cash = $all_cash_right_summ + $all_cash_left_summ;
                    $referral_network -> setPaymentsNetwork($bonus);//direct начисление статистики за текущиую итерацию сети
                    //dd($all_cash_left_summ);
                    $referral_network -> setPaymentsCash($payments_cash);//начисление single-line

                    //вычисление суммы к погашению и зачислению в проект
                    $repayment_amount =0;
                    //сумма к погашению в стеи и начислению в проект
                    if($summ_single_line_left_balance < $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_left_balance * 2) - ($bonus + $payments_cash);
                    }
                    elseif($summ_single_line_left_balance > $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_right_balance * 2) - ($bonus + $payments_cash);
                    }
                    elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                        $repayment_amount = ($summ_single_line_left_balance + $summ_single_line_right_balance) - ($bonus + $payments_cash);
                    }
                    //dd($repayment_amount);


                    //==============проводим погашение баланса пакетов пользователей в линии=============
                    //сформируем массивы баланса пакетов больше нуля с левой и с правой стороны
                    //dd($array_single_line_left);
                        $single_line_left_balance_pakege = [];
                        for($i = 0; $i < count($array_single_line_left); $i++){
                            if($array_single_line_left[$i] -> getBalance() > 0){
                                $single_line_left_balance_pakege[] = $array_single_line_left[$i];
                                $array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
                            }    
                        }
                        //dd($array_left_balance_pakege);
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
                    $referral_network -> setCurrentNetworkProfit($repayment_amount);// запись начисления погашаемой суммы в компанию
            
            
        }
     
     
        
        //запишем общий баланс дохода сети
        // foreach($referral_network_all as $all_cash){
        //     $array_balance_network_all_cash[] = $all_cash -> getCash();
        // }
        // foreach($referral_network_all as $all_direct){
        //     $array_balance_network_all_direct[] = $all_direct -> getCash();
        // }
        // foreach($pakege_all as $all_pakege_cash){
        //     $array_balance_network_all_pakege[] = $all_pakege_cash -> getPrice();
        // }
        //$profit_network_all = array_sum($array_balance_network_all_pakege) - (array_sum($array_balance_network_all_cash) + array_sum($array_balance_network_all_direct));
        //$list_network->setProfitNetwork($profit_network_all);
        

        $entityManager->persist($referral_network);
        $entityManager->flush();
    }
    


    
    private function singleThree($referral_network_left,$referral_network_right,$referral_network_user,$old_profit_network,$list_network,$referral_network,$bonus)
    {
        //первое выстраиваивание линии из трех участников реферальной сети  и начисление вознаграждений       
        if($referral_network_left -> getBalance() == $referral_network_right -> getBalance()){
            $balance_pakege_right = $referral_network_right -> getBalance();
            $balance_pred = $referral_network_left -> getBalance();
            $cash_refovod = $referral_network_user -> getCash();
            $referral_network_left -> setBalance(0);
            $referral_network_right -> setBalance(0);
            $reward_user = $referral_network_user ->  getReward();
            //$direct_user = $referral_network_user ->  getDirect();
            //$balance = $balance_pred * 0.1;
            //$reward = $reward_user + $balance;
            //$new_cash = $cash_refovod + $balance;
            //$referral_network_user ->  setReward($reward);
            //$referral_network_user -> setCash($new_cash);
            $new_profit_network = ($balance_pred + $balance_pakege_right) - $bonus;
            //$list_network ->setProfitNetwork($new_profit_network);
            $payments = $bonus;
            $referral_network -> setPaymentsNetwork($payments);
            $referral_network ->setCurrentNetworkProfit($new_profit_network);
        }
        elseif($referral_network_left -> getBalance() < $referral_network_right -> getBalance()){
            $balance_pred_left = $referral_network_left -> getBalance();
            $balance_pred_right = $referral_network_right -> getBalance();
            $repayment_balance = $balance_pred_left * 2;
            $cash_refovod = $referral_network_user -> getCash();
            $balance_right = $balance_pred_right - $balance_pred_left;
            $referral_network_left -> setBalance(0);
            $referral_network_right -> setBalance($balance_right);
            $reward_user = $referral_network_user -> getReward();
            $balance = $balance_pred_left * 0.1;
            $reward = $reward_user + $balance;
            $new_cash = $cash_refovod + $balance;
            $referral_network_user ->  setReward($reward);
            $referral_network_user -> setCash($new_cash);
            $new_profit_network = $repayment_balance - ($bonus + $balance);
            //$list_network ->setProfitNetwork($new_profit_network);
            $payments = $bonus + $balance;
            $referral_network -> setPaymentsNetwork($bonus);
            $referral_network -> setPaymentsCash($balance);
            $referral_network ->setCurrentNetworkProfit($new_profit_network);
        }
        elseif($referral_network_left -> getBalance() > $referral_network_right -> getBalance()){
            $balance_pred_left = $referral_network_left -> getBalance();
            $balance_pred_right = $referral_network_right -> getBalance();
            $repayment_balance = $balance_pred_right * 2;
            $cash_refovod = $referral_network_user -> getCash();
            $balance_left = $balance_pred_left - $balance_pred_right;
            $referral_network_left -> setBalance($balance_left);
            $referral_network_right -> setBalance(0);
            $reward_user = $referral_network_user ->  getReward();
            $balance = $balance_pred_right * 0.1;
            $reward = $reward_user + $balance;
            $referral_network_user ->  setReward($reward);
            $new_cash = $cash_refovod + $balance;
            $referral_network_user -> setCash($new_cash);
            $new_profit_network = $repayment_balance - ($bonus + $balance);
            $payments = $bonus + $balance;
            $referral_network -> setPaymentsNetwork($bonus);
            $referral_network -> setPaymentsCash($balance);
            //$list_network ->setProfitNetwork($new_profit_network);
            $referral_network -> setCurrentNetworkProfit($new_profit_network);
        } 
             
    }

    private function where_is_balance($referral_network_user,$summ_single_line_right_balance){
        //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
        $reward_refovod = $referral_network_user -> getReward();
        $cash_refovod = $referral_network_user -> getCash();
        $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
        $reward_user = $reward_refovod + $reward_right_user; 
        $new_cash = $cash_refovod + $reward_right_user;
        $referral_network_user -> setReward($reward_user);
        $referral_network_user -> setCash($new_cash);
    }

    private function reward_single_right_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back){
        //далее начинаем начисление наград участникам линии двигаясь в правую сторону перебирая массив участников 
        //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей суммы справа или слева,
        //если баланс имеется, то заново определяем баланс с каждой стороны, орпеделяем на какой стороне баланс меньше, начисляем награду от меньшей суммы
        $i = 1;
        $single_line_right_r = $single_line_right;
        $single_line_left_r = $single_line_left;
        $cash_all = [];
        while($i < count($single_line_right_r))
        {
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_right_r);// убираем одного пользователя с левой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left_r); $j++){
                    $single_line_left_balance_new[] = $single_line_left_r[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right_r); $k++){
                    $single_line_right_balance_new[] = $single_line_right_r[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $reward_user_new1 = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $cash_refovod = $user -> getCash();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                    $reward1 = $user -> getReward();
                    if($reward1 <= $limit_cash_back){
                        $new_cash = $cash_refovod + $reward_user_new1;
                        $user -> setCash($new_cash); 
                        $reward_user1 = $reward_user_new1 + $reward1; 
                        $user -> setReward($reward_user1);
                        $cash_all[] = $reward_user_new1;
                    }
                    
                    $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance < $summ_single_line_right_balance){
                    $reward_user_new2 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $cash_refovod2 = $user -> getCash();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    $reward2 = $user -> getReward();
                    if($reward2 <= $limit_cash_back){
                        $new_cash2 = $cash_refovod2 + $reward_user_new2; 
                        $reward_user2 = $reward_user_new2 + $reward2; 
                        $user -> setReward($reward_user2);
                        $user -> setCash($new_cash2);
                        $cash_all[] = $reward_user_new2;
                    }
                    
                    $entityManager->flush();   
                } 
                elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                    $reward_user_new2 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    // $cash_refovod2 = $user -> getCash();
                    // $reward2 = $user -> getReward();
                    // $new_cash2 = $cash_refovod2 + $reward_user_new2; 
                    // $reward_user2 = $reward_user_new2 + $reward2; 
                    // $user -> setReward($reward_user2);
                    // $user -> setCash($new_cash2);
                    // $entityManager->flush();   
                } 
                array_unshift($single_line_left_r, $user);//добавляем  пользователя  в массив с левой стороны
                
        }
        return $cash_all;
    } 
    
    
    private function reward_single_left_line($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back){
        //далее начинаем начисление наград участникам линии двигаясь в правую сторону перебирая массив участников 
        //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей суммы справа или слева,
        //если баланс имеется, то заново определяем баланс с каждой стороны, орпеделяем на какой стороне баланс меньше, начисляем награду от меньшей суммы
        $i = 1;
        $single_line_right_l = $single_line_right;
        $single_line_left_l = $single_line_left;
        $cash_all2 = [];
        while($i < count($single_line_left_l))
        {    
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_left_l);
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                
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
                    $reward_user_new3 = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $cash_refovod3 = $user -> getCash();
                    $reward3 = $user -> getReward();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    if($reward3 <= $limit_cash_back){
                        $new_cash3 = $cash_refovod3 + $reward_user_new3; 
                        $reward_user3 = $reward_user_new3 + $reward3; 
                        $user -> setReward($reward_user3);
                        $user -> setCash($new_cash3);
                        $cash_all2[] = $reward_user_new3;
                    }
                    
                    $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance_new < $summ_single_line_right_balance_new){
                    $reward_user_new4 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $cash_refovod4 = $user -> getCash();
                    $reward4 = $user -> getReward();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    if($reward4 <= $limit_cash_back){
                        $new_cash4 = $cash_refovod4 + $reward_user_new4;  
                        $reward_user4 = $reward_user_new4 + $reward4; 
                        $user -> setReward($reward_user4);
                        $user -> setCash($new_cash4);
                        $cash_all2[] = $reward_user_new4;
                    }
                    
                    $entityManager->flush();   
                }
                elseif($summ_single_line_left_balance_new == $summ_single_line_right_balance_new){
                    $reward_user_new4 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    // $cash_refovod4 = $user -> getCash();
                    // $reward4 = $user -> getReward();
                    // $new_cash4 = $cash_refovod4 + $reward_user_new4;  
                    // $reward_user4 = $reward_user_new4 + $reward4; 
                    // $user -> setReward($reward_user4);
                    // $user -> setCash($new_cash4);
                    // $entityManager->flush();   
                }
                array_unshift($single_line_right_l, $user);//добавляем  пользователя  в массив с правой стороны
        }
        return $cash_all2;
    }

    private function makeMemberCode($arr1,$id,$arr2,$arr3){
        //$arr[0] = arr1 - id сети
        //$id пакета нового участника сети
        //$arr[2] = arr2 -id пакета владельца сети
        //$arr[3] = arr3  
        $member_code = $arr1.'-'.$id.'-'.$arr2.'-'.$arr3;
        return $member_code;
    }


    private function cashBackSummRight($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back){
        
        //расчет сумм и количества участников путем записи сумм начисления в массив для начислений по кешбэк в линии
        $i = 1;
        $single_line_right_r = $single_line_right;
        $single_line_left_r = $single_line_left;
        $cash_all = [];
        while($i < count($single_line_right_r))
        {
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_right_r);// убираем одного пользователя с левой  стороны которого достали из массива , относительно которого рассчитываем баланс слева и справа
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left_r); $j++){
                    $single_line_left_balance_new[] = $single_line_left_r[$j] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right_r); $k++){
                    $single_line_right_balance_new[] = $single_line_right_r[$k] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                if($summ_single_line_left_balance_new > $summ_single_line_right_balance_new){
                    $reward_user_new1 = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    //$pakege_user_network = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета  участника реферальной сети
                    $reward1 = $user -> getReward();
                    //учитываем начисление КэшБэк если участник не превыисл коэффициент личтных выплат (коэффициент - сейчас 300%)
                    if($reward1 <= $limit_cash_back){
                        $cash_all[] = $reward_user_new1;   
                    }   
                }
                elseif($summ_single_line_left_balance < $summ_single_line_right_balance){
                    $reward_user_new2 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    $reward2 = $user -> getReward();
                    //учитываем начисление КэшБэк если участник не превыисл коэффициент личтных выплат (коэффициент - сейчас 300%)
                    if($reward2 <= $limit_cash_back){
                        $cash_all[] = $reward_user_new2;
                    }
                        
                } 
                elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                    $reward_user_new2 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    // $cash_refovod2 = $user -> getCash();
                    // $reward2 = $user -> getReward();
                    // $new_cash2 = $cash_refovod2 + $reward_user_new2; 
                    // $reward_user2 = $reward_user_new2 + $reward2; 
                    // $user -> setReward($reward_user2);
                    // $user -> setCash($new_cash2);
                    // $entityManager->flush();   
                } 
                array_unshift($single_line_left_r, $user);//добавляем  пользователя  в массив с левой стороны
                
        }
        return $cash_all;
    }
    
    private function cashBackSummLeft($single_line_right,$single_line_left,$single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back){
        //расчет сумм и количества участников путем записи сумм начисления в массив для начислений по кешбэк в линии
        $i = 1;
        $i = 1;
        $single_line_right_l = $single_line_right;
        $single_line_left_l = $single_line_left;
        $cash_all2 = [];
        while($i < count($single_line_left_l))
        {    
                $entityManager = $doctrine->getManager();
                $user = array_shift($single_line_left_l);
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива
                
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
                    $reward_user_new3 = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward3 = $user -> getReward();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    //учитываем начисление КэшБэк если участник не превыисл коэффициент личтных выплат (коэффициент - сейчас 300%)
                    if($reward3 <= $limit_cash_back){
                        $cash_all2[] = $reward_user_new3;
                    }
                }
                elseif($summ_single_line_left_balance_new < $summ_single_line_right_balance_new){
                    $reward_user_new4 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward4 = $user -> getReward();
                    $user_id_pakege = $user -> getPakage();
                    $limit_cash_back = $k_cash_back * $user_id_pakege;
                    //учитываем начисление КэшБэк если участник не превыисл коэффициент личтных выплат (коэффициент - сейчас 300%)
                    if($reward4 <= $limit_cash_back){
                        $cash_all2[] = $reward_user_new4;
                    }
                }
                elseif($summ_single_line_left_balance_new == $summ_single_line_right_balance_new){
                    $reward_user_new4 = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    // $cash_refovod4 = $user -> getCash();
                    // $reward4 = $user -> getReward();
                    // $new_cash4 = $cash_refovod4 + $reward_user_new4;  
                    // $reward_user4 = $reward_user_new4 + $reward4; 
                    // $user -> setReward($reward_user4);
                    // $user -> setCash($new_cash4);
                    // $entityManager->flush();   
                }
                array_unshift($single_line_right_l, $user);//добавляем  пользователя  в массив с правой стороны
        }
        return $cash_all2;
    }


    private function reward_cash_back_limit($single_line,$summ_single_line_left_balance,$summ_single_line_right_balance,$doctrine,$count_left, $count_right,$k_cash_back,$payments_cash_back_all_summ){
        $entityManager = $doctrine->getManager();
        array_pop($single_line); //убираем последнего участника (с правой стороны линии) сети которому не причитается выплата
        array_shift($single_line);//вырезаем первого участника массива (который имеет крайнюю позицию в линии слева) которому не причитается выплата 
        $cash_all = [];
        $reward_user_new1 = $payments_cash_back_all_summ;//контрольная для начисления награды кешбэк

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

}
