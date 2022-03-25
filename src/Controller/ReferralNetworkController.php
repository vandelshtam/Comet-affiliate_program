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
//         //=========формула расчета наград в сети при количестве участников 4 и более======
//         //получаем информацию о сети и записи участника предоставившего реферальную ссылку(рефовода)
//         $entityManager = $doctrine->getManager();
//         $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
//         $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => '23-49-48-qZg78nGIOCPirhHijUxY']);//объект пользователя представившего реферальную ссылку (рефовода извлекаем по реферальной ссылке referral_link)
//         $referral_network_user_new = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => '23-52-48-qZg78nGIOCPirhHijUxY']);//объект нового партнера в  реферальной  сети (получаем по member_code)
//         //$referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);
//         $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
//         //$referral_network_left_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('left',0);//получаем объект участников с левой стороны линии с балансом более "0"
//         $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
//         //$referral_network_right_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('right',0);//получаем объект участников с правой стороны линии с балансом более "0"
//         $user_referral_status = $referral_network_user -> getUserStatus();
//         $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);
//         $owner_array[] = $user_owner;//основатель сети

//         //построение линии сингл-лайт
//         $single_line = array_merge($referral_network_left, $owner_array, $referral_network_right);//объеденяем в один массив  соотвтетсвии с правилом построения линии сингл-лайн
//         for($i = 0; $i <= count($single_line); $i++){
//             if($single_line[$i] -> getMemberCode() == '23-49-48-qZg78nGIOCPirhHijUxY'){
//                 $key_user = $i;
//                 $single_line_id_refovod = $i;
//                 break;
//             }
//         }
//         $single_line_left = [];
//         for($i = 0; $i < $key_user; $i++){
//             $single_line_left[] = $single_line[$i];
//         }
//         $single_line_right = [];
//         for($i = $key_user; $i < count($single_line); $i++){
//             $single_line_right[] = $single_line[$i];
//         }
//         $array_single_line_right = $single_line_right;

//         //переворачиваем  массив пользователей с левой стороны линии в нормальный вид
//         $single_line_left = array_reverse($single_line_left);
//         $array_single_line_left = $single_line_left;

//         //gолучаем баланс левой и правой части линии
//         $single_line_left_balance = [];
//         for($i = 0; $i < count($single_line_left); $i++){
//             $single_line_left_balance[] = $single_line_left[$i] -> getBalance();
//         }
//         $summ_single_line_left_balance = array_sum($single_line_left_balance);
        
//         $single_line_right_balance = [];
//         for($i = 0; $i < count($single_line_right); $i++){
//             $single_line_right_balance[] = $single_line_right[$i] -> getBalance();
//         }
//         $summ_single_line_right_balance = array_sum($single_line_right_balance);

//         //dd($summ_single_line_right_balance);


//         //определяем с какой стороны линии сумма баланса больше
//         if($summ_single_line_left_balance == $summ_single_line_right_balance){
//                 //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
//                 $reward_refovod = $referral_network_user -> getReward();
//                 $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//                 $reward_user = $reward_refovod + $reward_right_user; 
//                 $referral_network_user -> setReward($reward_right_user);
//         }
//         else{

//             // $reward_refovod = $referral_network_user -> getReward();
//             // $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//             // $reward_user = $reward_refovod + $reward_right_user; 
//             // $referral_network_user -> setReward($reward_right_user);

//             //далее начинаем начисление наград участникам линии двигаясь в правую сторону перебирая массив участников справа
//             //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей скммы справа,
//             //если баланс имеется, то заново определяем меньшую сторну баланс и начисляем награду от меньшей суммы
                
//             $i = 1; 
//             while($i < count($single_line_right)){
                
//                 $user = array_shift($single_line_right);
                
//                 $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива

//                 //получаем баланс левой и правой части линии
//                 $single_line_left_balance_new = [];
//                 for($j = 0; $j < count($single_line_left); $j++){
//                     $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
//                 }
//                 $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
//                 $single_line_right_balance_new = [];
//                 for($k = 0; $k < count($single_line_right); $k++){
//                     $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
//                 }
//                 $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

//                 if($summ_single_line_left_balance > $summ_single_line_right_balance){
//                     $reward_user_new = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//                     $reward_user = $reward_user_new + $reward; 
//                     $user -> setReward($reward_user);
//                     $entityManager->flush();   
//                 }
//                 if($summ_single_line_left_balance < $summ_single_line_right_balance){
//                     $reward_user_new = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//                     $reward_user = $reward_user_new + $reward; 
//                     $user -> setReward($reward_user);
//                     $entityManager->flush();   
//                 } 
//                 $reward1[] = $reward;   
//             }

//             //теперь проеделываем операции по определению наград двигаясь в левую сторону от рефовода по линии 
//             $i = 1;
//             while($i < count($single_line_left)){
                
//                 $user = array_shift($single_line_left);
                
//                 $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива

//                 //получаем баланс левой и правой части линии
//                 $single_line_left_balance_new = [];
//                 for($j = 0; $j < count($single_line_left); $j++){
//                     $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
//                 }
//                 $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
//                 $single_line_right_balance_new = [];
//                 for($k = 0; $k < count($single_line_right); $k++){
//                     $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
//                 }
//                 $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

//                 if($summ_single_line_left_balance > $summ_single_line_right_balance){
//                     $reward_user_new = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//                     $reward_user = $reward_user_new + $reward; 
//                     $user -> setReward($reward_user);
//                     $entityManager->flush();   
//                 }
//                 if($summ_single_line_left_balance < $summ_single_line_right_balance){
//                     $reward_user_new = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
//                     $reward_user = $reward_user_new + $reward; 
//                     $user -> setReward($reward_user);
//                     $entityManager->flush();
//                     //dd($reward_user);     
//                 } 
//                 $reward2[] = $reward;   
//             }

//         }


//         //проводим погашение баланса покетов пользователей в линии
//         //сформируем массивы баланса пакетов больше нуля с левой и с правой стороны
// //dd($single_line_left);
//         $single_line_left_balance_pakege = [];
//         for($i = 0; $i < count($array_single_line_left); $i++){
//             if($array_single_line_left[$i] -> getBalance() > 0){
//                 $single_line_left_balance_pakege[] = $array_single_line_left[$i];
//                 $array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
//             }    
//         }
//         $summ_left_balance_pakege = array_sum($array_left_balance_pakege);
//         $count_left_balance_pakege = count($array_left_balance_pakege);
//         //dd($single_line_left_balance_pakege);

//         $single_line_right_balance_pakege = [];
//         for($i = 0; $i < count($array_single_line_right); $i++){
//             if($array_single_line_right[$i] -> getBalance() > 0){
//                 $single_line_right_balance_pakege[] = $array_single_line_right[$i];
//                 $array_right_balance_pakege[] = $array_single_line_right[$i] -> getBalance();
//             }
//         }
//         $summ_right_balance_pakege = array_sum($array_right_balance_pakege);
//         $count_right_balance_pakege = count($array_right_balance_pakege);

//         //dd($summ_right_balance_pakege);
        

//         if($summ_left_balance_pakege > $summ_right_balance_pakege){
            
//             //dd($summ_middle1);
//             for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
//                 $summ_middle1 = $summ_right_balance_pakege/$count_left_balance_pakege;
//                 $balance_old1 = $single_line_left_balance_pakege[$i] -> getBalance();
//                 $balance_new1 = $balance_old1 - $summ_middle1;
//                 $single_line_left_balance_pakege[$i] -> setBalance($balance_new1);
//                 $entityManager->flush();
//                 //dd($count_left_balance_pakege);
//             }
//             for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
//                 $summ_middle2 = $summ_right_balance_pakege/$count_right_balance_pakege;
//                 $balance_old2 = $single_line_right_balance_pakege[$i] -> getBalance();
//                 $balance_new2 = $balance_old2 - $summ_middle2;
//                 $single_line_right_balance_pakege[$i] -> setBalance($balance_new2);
//                 $entityManager->flush();
//             }
//         }
//         //dd($single_line_right_balance);
//         if($summ_left_balance_pakege < $summ_right_balance_pakege){
            
//             //dd($summ_middle2);
//             for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
//                 $summ_middle2 = $summ_left_balance_pakege/$count_right_balance_pakege;
//                 $balance_old3 = $single_line_right_balance_pakege[$i] -> getBalance();
//                 $balance_new3 = $balance_old3 - $summ_middle2;
//                 $single_line_right_balance_pakege[$i] -> setBalance($balance_new3);
//                 $entityManager->flush();
//             }
//             for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
//                 $summ_middle2 = $summ_left_balance_pakege/$count_left_balance_pakege;
//                 $balance_old4 = $single_line_left_balance_pakege[$i] -> getBalance();
//                 $balance_new4 = $balance_old4 - $summ_middle2;
//                 $single_line_left_balance_pakege[$i] -> setBalance($balance_new4);
//                 $entityManager->flush();
//             }
//         }
    
        //dd($single_line_left_balance);
        //первое выстраиваивание линии из трех участников реферальной сети
        // if($referral_network_count == 3){
            
        //     if($referral_network_left -> getBalance() == $referral_network_right -> getBalance()){
        //         $balance_pred = $referral_network_left -> getBalance();
        //         $referral_network_left -> setBalance(0);
        //         $referral_network_right -> setBalance(0);
        //         $reward_user = $referral_network_user ->  getReward();
        //         $balance = $balance_pred * 0.1;
        //         $reward = $reward_user + $balance;
        //         $referral_network_user ->  setReward($reward);
        //     }
        //     if($referral_network_left -> getBalance() < $referral_network_right -> getBalance()){
        //         $balance_pred_left = $referral_network_left -> getBalance();
        //         $balance_pred_right = $referral_network_right -> getBalance();
        //         $balace_right = $balance_pred_right - $balance_pred_left;
        //         $referral_network_left -> setBalance(0);
        //         $referral_network_right -> setBalance($balace_right);
        //         $reward_user = $referral_network_user ->  getReward();
        //         $balance = $balance_pred_left * 0.1;
        //         $reward = $reward_user + $balance;
        //         $referral_network_user ->  setReward($reward);
        //     }
        //     if($referral_network_left -> getBalance() > $referral_network_right -> getBalance()){
        //         $balance_pred_left = $referral_network_left -> getBalance();
        //         $balance_pred_right = $referral_network_right -> getBalance();
        //         $balace_left = $balance_pred_left - $balance_pred_right;
        //         $referral_network_left -> setBalance($balace_left);
        //         $referral_network_right -> setBalance(0);
        //         $reward_user = $referral_network_user ->  getReward();
        //         $balance = $balance_pred_right * 0.1;
        //         $reward = $reward_user + $balance;
        //         $referral_network_user ->  setReward($reward);
        //     }    
        // }
        
        //dd($referral_network_left_balance);
        //$entityManager->flush();
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

    #[Route('/myplace', name: 'app_referral_network_myplace', methods: ['GET', 'POST'])]
    public function my_зlace(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine)
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        //$referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $id]);
        //$pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        if ($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $user_id]) == true) {
            //проводим предварительное создание записи в таблицу строки нового участника реферальной сети (рефовод)
            $id = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $user_id]) -> getId();
            //dd($id);
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
        //$user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        $user_referral_status = $referral_network -> getUserStatus();
        $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);
        $owner_array[] = $user_owner;//основатель сети
        $reward = $referral_network -> getReward();

        //построение линии сингл-лайт в виде массива
        $single_line = array_merge($referral_network_left, $owner_array, $referral_network_right);//объеденяем в один массив в  соотвтетсвии с правилом построения линии сингл-лайн
        //dd(count($single_line));
        //получаем ключ положения пользователя в массиве объектов участников реферальной сети
        $j = 0;
        for($i = 0; $i < count($single_line); $i++){
            if($single_line[$i] -> getId() == $id){
                $j += 1;
                $key_user[] = $i;
                //dd($single_line[$i] -> getId());
               // break;
            }
        }
        //dd($key_user);
        //dd($single_line);
        $single_line_left = [];
        for($i = 0; $i < $key_user[0]; $i++){
            $single_line_left[] = $single_line[$i];
        }
        $single_line_right = [];
        for($i = $key_user[0] + 1; $i < count($single_line); $i++){
            $single_line_right[] = $single_line[$i];
        }
        

        //gолучаем баланс левой и правой части линии
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

        //dd($array_data);
        return $this->render('referral_network/show.html.twig', [
            'referral_network' => $referralNetwork,
            'data' => $array_data,
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
        //dd($member_code);
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();//получем две самых новых по времени записи в реферальной сети (в таблице)
        $status_user = $referral_network_status[1]->getUserStatus();//получаем запись самого нового участника сети ,определяем его положение в линии "слева" или "справа"
        $status = $this -> status($status_user);// присваеваем новому участнику сети положение в линии  слева или справа
        $referral_network_referral = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//получаем объект пользователя участника  реферальной сети который предоставил реферальную ссылку
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем данные нового участника в реферальной сети (рефовод) чтобы дополнить информацию всеми необходимыми данными
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета нового участника реферальной сети
        
        //dd($referral_network);
        $arr = explode('-', $member_code);//уникальный код участника сети преобразуем в массив для получения важных данных
        $listReferralNetwork_id = $arr[0];//айди реферальной сети
        $pakege_user_id = $arr[1];//айди пакета нового участника сети
        $user_id = $pakege_user -> getUserId();//айди нвого участника сети
        $balance = $pakege_user -> getPrice();//стоимость пакета нового участника сети
        
        
	    //получаем объект записи родительской реферальной сети и получем из нее код этой сети
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();

        //изменения статуса пакета приглашенного участника сети на "активирован"
        $pakege_user -> setActivation('активирован');

        //данные пользователя который предосавил реферальную ссылку
        $network_referral_id = $referral_network_referral -> getId();//айди записи участника реферальной сети предоставившего ссылку
        $user_referral_id = $referral_network_referral -> getUserId();//пользователя системы участвующего в реферальной сети и предоставившего реферальную ссылку
//dd($user_referral_id);
        //расчет награды за приглашенного участника члену сети предоставишему реферальную ссылку (рефовода)
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

        if($referral_network_count > 3){
            return $this->redirectToRoute('app_referral_network_single', ['member_code' => $member_code, 'id' => $id, 'referral_link' => $referral_link], Response::HTTP_SEE_OTHER);
        }

        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', ['member_code' => $member_code, 'id' => $id, 'referral_link' => $referral_link], Response::HTTP_SEE_OTHER);
        
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

    #[Route('/{member_code}/{id}/{referral_link}/new/singl', name: 'app_referral_network_single', methods: ['GET', 'POST'])]
    public function single(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine,string $member_code, int $id, string $referral_link): Response
    {
        //dd($referral_link);
        //=========формула расчета наград в сети при количестве участников 4 и более======
        //получаем информацию о сети и записи участника предоставившего реферальную ссылку(рефовода)
        $entityManager = $doctrine->getManager();
        $referral_network_count = $entityManager->getRepository(ReferralNetwork::class)->findByCountField();
        $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]);//объект пользователя представившего реферальную ссылку (рефовода извлекаем по реферальной ссылке referral_link)
        $referral_network_user_new = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//объект нового партнера в  реферальной  сети (получаем по member_code)
        //$referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'left']);
        $referral_network_left = $entityManager->getRepository(ReferralNetwork::class)->findByLeftField(['left']);//получаем объект всех участников с левой стороны линии
        //$referral_network_left_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('left',0);//получаем объект участников с левой стороны линии с балансом более "0"
        $referral_network_right = $entityManager->getRepository(ReferralNetwork::class)->findByRightField(['right']);//получаем объект участников участников с правой стороны 
        //$referral_network_right_balance = $entityManager->getRepository(ReferralNetwork::class)->findByBalanceField('right',0);//получаем объект участников с правой стороны линии с балансом более "0"
        $user_referral_status = $referral_network_user -> getUserStatus();
        $user_owner = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_status' => 'owner']);
        $owner_array[] = $user_owner;//основатель сети

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

        //переворачиваем  массив пользователей с левой стороны линии в нормальный вид
        //$single_line_left = array_reverse($single_line_left);
        $array_single_line_left = $single_line_left;

        //gолучаем баланс левой и правой части линии
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

        //dd($summ_single_line_left_balance);


        //определяем с какой стороны линии сумма баланса больше

        // if($summ_single_line_left_balance == 0){
        //     //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
        //     $reward_refovod = $referral_network_user -> getReward();
        //     $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
        //     $reward_user = $reward_refovod + $reward_right_user; 
        //     $referral_network_user -> setReward($reward_right_user);
        //     $entityManager->flush(); 
        // }
        if($summ_single_line_right_balance == 0 or $summ_single_line_left_balance == 0){
            //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
            $reward_refovod = $referral_network_user -> getReward();
            $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
            $reward_user = $reward_refovod + $reward_right_user; 
            $referral_network_user -> setReward($reward_right_user);
            $entityManager->flush(); 
        }

        elseif($summ_single_line_left_balance == $summ_single_line_right_balance){
                //вычислим и запишем награду участнику относительно которого выстроена линия (рефовод)
                $reward_refovod = $referral_network_user -> getReward();
                $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                $reward_user = $reward_refovod + $reward_right_user; 
                $referral_network_user -> setReward($reward_right_user);
                $entityManager->flush(); 

                while($i <= count($single_line_right)){
                $user = array_shift($single_line_right);
                $user -> setReward(0);
                $entityManager->flush(); 
                } 
                while($i <= count($single_line_left)){
                    $user = array_shift($single_line_left);
                    $user -> setReward(0);
                    $entityManager->flush(); 
                    }   

        }
        else{

            // $reward_refovod = $referral_network_user -> getReward();
            // $reward_right_user = $summ_single_line_right_balance *0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
            // $reward_user = $reward_refovod + $reward_right_user; 
            // $referral_network_user -> setReward($reward_right_user);

            //далее начинаем начисление наград участникам линии двигаясь в правую сторону перебирая массив участников справа
            //достаем участника из массива проверяем его баланс если нулевой начисляем награду от меньшей скммы справа,
            //если баланс имеется, то заново определяем меньшую сторну баланс и начисляем награду от меньшей суммы
            array_unshift($single_line_right, $referral_network_user);//добавляем рефовода который прелоставил ссылку в массив    
            $i = 1; 
            while($i < count($single_line_right)){
                
                $user = array_shift($single_line_right);
                
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива

                //получаем баланс левой и правой части линии
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left); $j++){
                    $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right); $k++){
                    $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                if($summ_single_line_left_balance >= $summ_single_line_right_balance){
                    $reward_user_new = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward_user = $reward_user_new + $reward; 
                    $user -> setReward($reward_user);
                    $entityManager->flush();   
                }
                if($summ_single_line_left_balance < $summ_single_line_right_balance){
                    $reward_user_new = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward_user = $reward_user_new + $reward; 
                    $user -> setReward($reward_user);
                    $entityManager->flush();   
                } 
                //$reward1[] = $reward;   
            }

            //теперь проеделываем операции по определению наград двигаясь в левую сторону от рефовода по линии 
            $i = 1;
            while($i < count($single_line_left)){
                
                $user = array_shift($single_line_left);
                
                $reward = $user -> getReward();//текущие награды каждого юзера вызанного из массива

                //получаем баланс левой и правой части линии
                $single_line_left_balance_new = [];
                for($j = 0; $j < count($single_line_left); $j++){
                    $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
                }
                $summ_single_line_left_balance_new = array_sum($single_line_left_balance_new);
                    
                $single_line_right_balance_new = [];
                for($k = 0; $k < count($single_line_right); $k++){
                    $single_line_left_balance_new[] = $single_line[$i] -> getBalance();
                }
                $summ_single_line_right_balance_new = array_sum($single_line_right_balance_new);

                if($summ_single_line_left_balance >= $summ_single_line_right_balance){
                    $reward_user_new = $summ_single_line_right_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward_user = $reward_user_new + $reward; 
                    $user -> setReward($reward_user);
                    $entityManager->flush();   
                }
                if($summ_single_line_left_balance < $summ_single_line_right_balance){
                    $reward_user_new = $summ_single_line_left_balance_new * 0.1;//контрольная сумма баланса правой части линии по которой начисляются награды
                    $reward_user = $reward_user_new + $reward; 
                    $user -> setReward($reward_user);
                    $entityManager->flush();
                    //dd($reward_user);     
                } 
                //$reward2[] = $reward;   
            }

        


                //проводим погашение баланса покетов пользователей в линии
                //сформируем массивы баланса пакетов больше нуля с левой и с правой стороны
        //dd($summ_single_line_right_balance_new);
                $single_line_left_balance_pakege = [];
                for($i = 0; $i < count($array_single_line_left); $i++){
                    if($array_single_line_left[$i] -> getBalance() > 0){
                        $single_line_left_balance_pakege[] = $array_single_line_left[$i];
                        $array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
                    }    
                }
                //dd($single_line_left_balance_pakege);
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

                //dd($summ_right_balance_pakege);
                

                if($summ_left_balance_pakege > $summ_right_balance_pakege){
                    $summ_remainder = $summ_left_balance_pakege - $summ_right_balance_pakege;
                    //dd($summ_middle1);

                    for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                        
                        $balance_old1 = $single_line_left_balance_pakege[$i] -> getBalance();
                        $participation_rate = $balance_old1 / $summ_left_balance_pakege;
                        //dd($participation_rate);
                        $single_line_left_balance_pakege[$i] -> setKoef($participation_rate);
                        $entityManager->flush();
                    }
                    //dd($participation_rate);
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
                    //dd($summ_middle1);

                    for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                        
                        $balance_old2 = $single_line_right_balance_pakege[$i] -> getBalance();
                        $participation_rate2 = $balance_old2 / $summ_right_balance_pakege;
                        //dd($participation_rate2);
                        $single_line_right_balance_pakege[$i] -> setKoef($participation_rate2);
                        $entityManager->flush();
                    }
                    //dd($balance_old2);
                    for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                        
                        $participation_rate_user2 = $single_line_right_balance_pakege[$i] -> getKoef();
                        //dd($single_line_left_balance_pakege);
                        $new_balance_user2 = $participation_rate_user2 * $summ_remainder2;
                        $single_line_right_balance_pakege[$i] -> setBalance($new_balance_user2);
                        $entityManager->flush();
                    }

                    for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                        
                        $single_line_left_balance_pakege[$i] -> setBalance(0);
                        $entityManager->flush();
                    }
                }

                //dd($single_line_right_balance);
                // if($summ_left_balance_pakege < $summ_right_balance_pakege){
                    
                //     //dd($summ_middle2);
                //     for($i = 0; $i < count($single_line_right_balance_pakege); $i++){
                //         $summ_middle2 = $summ_left_balance_pakege/$count_right_balance_pakege;
                //         $balance_old3 = $single_line_right_balance_pakege[$i] -> getBalance();
                //         $cash_balance2 = 0;
                //         if($balance_old3 < $summ_middle2){
                //             $cash_balance2 +=  $summ_middle2 - $balance_old3;
                //             $balance_new3 = 0;
                //         }
                //         else{
                //            $balance_new3 = $balance_old3 - $summ_middle2;
                //         }
                //         $single_line_right_balance_pakege[$i] -> setBalance($balance_new3);
                //         $entityManager->flush();
                //     }
                //     if($cash_balance2 != 0){
                //     //перераспределяем остаток не "сгоревшего" баланса на участников у кторых баланс не равен "0"
                //     $single_line_right_balance_pakege_cash = [];
                //     for($i = 0; $i < count($array_single_line_right); $i++){
                //         if($array_single_line_right[$i] -> getBalance() > 0){
                //             $single_line_right_balance_pakege_cash[] = $array_single_line_right[$i];
                //             //$array_left_balance_pakege[] = $array_single_line_left[$i] -> getBalance();
                //         }
                //     }
                //     $cash_balance_middle2 =  $cash_balance2 / count($single_line_right_balance_pakege_cash);
                //     for($i = 0; $i < count($single_line_left_balance_pakege_cash); $i++){
                //         $balance_cash2 = $single_line_right_balance_pakege_cash[$i] -> getBalance();
                //         $new_balance2 = $balance_cash2 + $cash_balance_middle2;
                //         $single_line_right_balance_pakege_cash[$i] -> setBalance($new_balance2);
                //         $entityManager->flush();
                //     }
                //     }


                //     for($i = 0; $i < count($single_line_left_balance_pakege); $i++){
                //         $summ_middle2 = $summ_left_balance_pakege/$count_left_balance_pakege;
                //         $balance_old4 = $single_line_left_balance_pakege[$i] -> getBalance();
                //         $balance_new4 = $balance_old4 - $summ_middle2;
                //         $single_line_left_balance_pakege[$i] -> setBalance($balance_new4);
                //         $entityManager->flush();
                //     }
                // }
        }
        
        // $entityManager->persist($referral_network);
        // $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
    }


    #[Route('/{member_code}/{id}/{referral_link}/new/singl/three', name: 'app_referral_network_single_three', methods: ['GET', 'POST'])]
    public function singleThree(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine,string $member_code, int $id, string $referral_link): Response
    {
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

        // $entityManager->persist($referral_network);
        // $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
    }

}
