<?php

namespace App\Controller;

use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Form\ReferralNetworkType;
use App\Entity\ListReferralNetworks;
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
        //=========формула расчета наград в сети при количестве участников 4 и более======
        //получаем информацию о сети и записи участника предоставившего реферальную ссылку(рефовода)
        $entityManager = $doctrine->getManager();
        $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
        $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => '23-49-48-qZg78nGIOCPirhHijUxY']);//объект пользователя представившего реферальную ссылку (рефовода извлекаем по реферальной ссылке referral_link)
        $referral_network_user_new = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => '23-52-48-qZg78nGIOCPirhHijUxY']);//объект нового партнера в  реферальной  сети (получаем по member_code)
        //$referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        $referral_network_left_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('left',0);//получаем объект участников с левой стороны линии с балансом более "0"
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['right']);//получаем объект участников участников с правой стороны 
        $referral_network_right_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('right',0);//получаем объект участников с правой стороны линии с балансом более "0"
        $user_referral_status = $referral_network_user -> getUserStatus();

        //если участник передалвший реферальную ссылку участник левой части линии то перераспределяем всю линию на правую и левую части относительно него
        if($user_referral_status == 'left'){
            //dd($referral_network_right_balance);
            //получаем id участников сети с лефой стороны, первого, последнего и передавшего реферальную ссылку относительнокоторого выстраиваем линию
            $network_left_first = $entityManager->getRepository(ReferralNetwork::class)->findByIdFirstField();
            $network_id_left_first = $network_left_first[0]->getId();
            $network_left_last = $entityManager->getRepository(ReferralNetwork::class)->findByIdLastField();
            $network_id_left_last = $network_left_last[0]->getId();
            $network_refovod_id = $referral_network_user -> getId();//id участника реферальной сети предоставившего ссылку (рефовод) относительно которого выстраиваем линию



            

            //формируем линию участников сети с правой стороны
            $network_right_to_refovod = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceToPointField('right',0,$network_refovod_id);//объект записей участников которые нужно присоединить к в левую сторону
            $network_right_from_refovod = $entityManager->getRepository(ReferralNetwork::class)->findByBalancefromPointField('right',0,$network_refovod_id);//объект участников сети которые встают с правой стороны

            //формируем линию участников сети с левой стороны 
            //$ee = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceToPointField('left',0,$network_refovod_id);
            //$eee = $entityManager->getRepository(ReferralNetwork::class)->findByBalancefromPointField('left',0,$network_refovod_id);
            foreach($referral_network_left as $network){
                $arr_balance_left[] = $network -> getBalance();
            }
            foreach($referral_network_left as $network2){
                $network_right_to_refovod[] = $network2 -> getBalance();
            }
            //получаем баланс участников левой стороны линии
            $summ1 = array_sum($arr_balance_left);
            $summ2 = array_sum($network_right_to_refovod);
            $summ_balance_left = $summ1 + $summ2;
            $count1 = count($arr_balance_left);
            $count2 = count($network_right_to_refovod);
            $count_left = $count1 + $count2;


            //получаем баланс участников с правой стороны 
            foreach($referral_network_right_balance as $network3){
                $arr_balance_right[] = $network3 -> getBalance();
            }
            $summ_right = array_sum($arr_balance_right);
            $count_right = count($arr_balance_right);

            //получаем награду участника сети пригласившего нового партнера и записываем ее
            $reward_refovod = $referral_network_user_new -> getBalance();
            $balance_refovod = $referral_network_user_new -> getBalance();
            $summ_balance_refovod = $reward_refovod + $balance_refovod;
            $referral_network_user_new -> setBalance($summ_balance_refovod);


            //сравниваем баланс с левой и с правой стороны и начисляем награды
            if($summ_balance_left > $summ_right){
                $reward = $summ_balance_left *0.1;
            }
            if($summ_balance_left < $summ_right){
                $reward = $summ_right * 0.1;
            }
            if($summ_balance_left == $summ_right){

            }
        }
        dd($summ1);
        //dd($user_referral_status);



        $referral_network_user_id = $referral_network_user -> getId();
        //первое выстраиваивание линии из трех участников реферальной сети
        //if($referral_network_count > 3){
            
            if($referral_network_left -> getBalance() == $referral_network_right -> getBalance()){
                $balance_pred = $referral_network_left -> getBalance();
                $referral_network_left -> setBalance(0);
                $referral_network_right -> setBalance(0);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }
            if($referral_network_left -> getBalance() < $referral_network_right -> getBalance()){
                $balance_pred_left = $referral_network_left -> getBalance();
                $balance_pred_right = $referral_network_right -> getBalance();
                $balace_right = $balance_pred_right - $balance_pred_left;
                $referral_network_left -> setBalance(0);
                $referral_network_right -> setBalance($balace_right);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred_left * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }
            if($referral_network_left -> getBalance() > $referral_network_right -> getBalance()){
                $balance_pred_left = $referral_network_left -> getBalance();
                $balance_pred_right = $referral_network_right -> getBalance();
                $balace_left = $balance_pred_left - $balance_pred_right;
                $referral_network_left -> setBalance($balace_left);
                $referral_network_right -> setBalance(0);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred_right * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }    
        //}
        
        dd($referral_network_left_balance);
        return $this->render('referral_network/index.html.twig', [
            'referral_networks' => $referralNetworkRepository->findAll(),
        ]);
    }

    #[Route('/{referral_link}/{id}/new', name: 'app_referral_network_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, string $referral_link, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['network_code' => $referral_link]);
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $arr = explode('-', $referral_link);//уникальный персональный код участника сети со статусом владелец сети преобразуем в массив для извлечения информации о участнике предоставившегго реферальную ссылку
        //$arr[0] - id сети
        //$id пакета нового участника сети
        //$arr[2]id пакета владельца сети
        //$arr[3] 
        $member_code = $arr[0].'-'.$id.'-'.$arr[2].'-'.$arr[3];//уникальный персональный код  нового участника сети пришедшего по реферальной ссылке (рефовод)
        //dd($member_code);

        $referralNetwork = new ReferralNetwork();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

         $user = $this -> getUser();
         $username = $user -> getUsername();
        
        if ($form->isSubmitted() && $form->isValid()) {
            //проводим предварительное создание записи в таблицу строки нового участника реферальной сети (рефовод)
            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_new_confirm', ['member_code' => $member_code, 'id' => $id, 'referral_link' => $referral_link], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('referral_network/new.html.twig', [
            'referral_network' => $referralNetwork,
            'form' => $form,
            'username' => $username,
            'member_code' => $member_code,
        ]);
    }

    #[Route('/{id}', name: 'app_referral_network_show', methods: ['GET'])]
    public function show(ReferralNetwork $referralNetwork): Response
    {
        return $this->render('referral_network/show.html.twig', [
            'referral_network' => $referralNetwork,
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

    #[Route('/{member_code}/{id}/{referral_link}/new/confirm', name: 'app_referral_network_new_confirm', methods: ['GET', 'POST'])]
    public function newConfirm(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, string $member_code, int $id, string $referral_link): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();//получем две самых новых по времени записи в реферальной сети (в таблице)
        $status_user = $referral_network_status[1]->getUserStatus();//получаем запись самого нового участника сети ,определяем его положение в линии "слева" или "справа"
        $status = $this -> status($status_user);// присваеваем новому участнику сети положение в линии  слева или справа
        $referral_network_referral = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//получаем объект пользователя участника  реферальной сети который предоставил реферальную ссылку
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем данные нового участника в реферальной сети (рефовод) чтобы дополнить информацию всеми необходимыми данными
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета нового участника реферальной сети
        
        $arr = explode('-', $member_code);//уникальный код участника сети преобразуем в массив для получения важных данных
        $listReferralNetwork_id = $arr[0];//айди реферальной сети
        $pakege_user_id = $arr[1];//айди пакета нового участника сети
        $user_id = $referral_network -> getUserId();//айди нвого участника сети
        $balance = $pakege_user -> getPrice();//стоимость пакета нового участника сети
        
	    //получаем объект записи родительской реферальной сети и получем из нее код этой сети
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();

        //изменения статуса пакета приглашенного участника сети на "активирован"
        $pakege_user -> setActivation('активирован');

        //данные пользователя который предосавил реферальную ссылку
        $network_referral_id = $referral_network_referral -> getId();//айди записи участника реферальной сети предоставившего ссылку
        $user_referral_id = $referral_network_referral -> getUserId();//пользователя системы участвующего в реферальной сети и предоставившего реферальную ссылку

        //расчет награды за приглашенного участника члену сети предоставишему реферальную ссылку
        $bonus = $balance * 0.1;
        $referral_network_referral_bonus = $referral_network_referral -> getReward();
        $reward = $bonus + $referral_network_referral_bonus;
        $referral_network_referral -> setReward($reward);
        
        //записываем и сохраняем в таблицу участника реферальной сети все нужные данные
        $user = $this -> getUser();    
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus($status);
        $referral_network -> setPakegeId($pakege_user_id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setBalance($balance);
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setUserReferralId($user_referral_id);
        $referral_network -> setNetworkReferralId($network_referral_id);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referralNetworkRepository->add($referral_network);



        //========выполнение формулы одной линии========= 
        $entityManager = $doctrine->getManager();
        $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
        $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//объект пользователя представившего реферальную ссылку
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'right']);
        //dd($referral_network_left);
        $referral_network_user_id = $referral_network_user -> getId();

        //первое выстраиваивание линии из трех участников реферальной сети
        if($referral_network_count == 3){
            
            if($referral_network_left -> getBalance() == $referral_network_right -> getBalance()){
                $balance_pred = $referral_network_left -> getBalance();
                $referral_network_left -> setBalance(0);
                $referral_network_right -> setBalance(0);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }
            if($referral_network_left -> getBalance() < $referral_network_right -> getBalance()){
                $balance_pred_left = $referral_network_left -> getBalance();
                $balance_pred_right = $referral_network_right -> getBalance();
                $balace_right = $balance_pred_right - $balance_pred_left;
                $referral_network_left -> setBalance(0);
                $referral_network_right -> setBalance($balace_right);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred_left * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }
            if($referral_network_left -> getBalance() > $referral_network_right -> getBalance()){
                $balance_pred_left = $referral_network_left -> getBalance();
                $balance_pred_right = $referral_network_right -> getBalance();
                $balace_left = $balance_pred_left - $balance_pred_right;
                $referral_network_left -> setBalance($balace_left);
                $referral_network_right -> setBalance(0);
                $reward_user = $referral_network_user ->  getReward();
                $balance = $balance_pred_right * 0.1;
                $reward = $reward_user + $balance;
                $referral_network_user ->  setReward($reward);
            }    
        }


        $entityManager->persist($referral_network);
        $entityManager->persist($referral_network_referral);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
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

    #[Route('/singl', name: 'app_referral_network_single', methods: ['GET', 'POST'])]
    public function single(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByCountField()();
        // $status_user = $referral_network_status[1]->getUserStatus();
        // $status = $this -> status($status_user);
        // $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);
        // $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        
        // $arr = explode('-', $member_code);
        // $listReferralNetwork_id = $arr[0];
        // $pakege_user_id = $arr[1];
        // $user_id = $referral_network -> getId();
        // $balance = $pakege_user -> getPrice();
        
        
        // //dd($balance);
        // //$referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();

	    // //dd($referral_network_status);
        // $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        // $network_code = $listReferralNetwork -> getNetworkCode();
        // //dd($form);

        // $user = $this -> getUser();    
        // $referral_network -> setUserId($user_id);
        // $referral_network -> setUserStatus($status);
        // $referral_network -> setPakegeId($pakege_user_id);
        // $referral_network -> setNetworkId($listReferralNetwork_id);
        // $referral_network -> setBalance($balance);
        // $referral_network -> setNetworkCode($network_code);
        // $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        // $referralNetworkRepository->add($referral_network);
        dd($referral_network_status);
        if($referral_network_status == 3)
        
        // $entityManager->persist($referral_network);
        // $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
    }

}
