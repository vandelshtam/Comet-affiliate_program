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
    {dd($id);
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();//получем две самых новых по времени записи в реферальной сети (в таблице)
        $status_user = $referral_network_status[1]->getUserStatus();//получаем запись самого нового участника сети ,определяем его положение в линии "слева" или "справа"
        $status = $this -> status($status_user);// присваеваем новому участнику сети положение в линии  слева или справа
        $referral_network_referral = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем объект пользователя участника  реферальной сети который предоставил реферальную ссылку
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);//получаем данные нового участника в реферальной сети (рефовод) чтобы дополнить информацию всеми необходимыми данными
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);// получаем оъект пакета нового участника реферальной сети
        
        $arr = explode('-', $member_code);//уникальный код участника сети преобразуем в массив для получения важных данных
        $listReferralNetwork_id = $arr[0];//айди реферальной сети
        $pakege_user_id = $arr[1];//айди пакета нового участника сети
        $user_id = $referral_network -> getId();//айди нвого участника сети
        $balance = $pakege_user -> getPrice();//стоимость пакета нового участника сети
        
	    //получаем объект записи родительской реферальной сети и получем из нее код этой сети
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();

        //данные пользователя который предосавил реферальную ссылку
        $network_referral_id = $referral_network_referral -> getId();//айди записи участника реферальной сети предоставившего ссылку
        $user_referral_id = $referral_network_referral -> getUserId();//пользователя системы участвующего в реферальной сети и предоставившего реферальную ссылку
        //расчет награды за приглашенного участника члену сети предоставишему реферальную ссылку
        $reward = $balance * 0.1;
        $referral_network_referral -> setReward($reward);
        dd($referral_network_referral);
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
            if($status_user == 'left'){
                $status_u = 'right';
            }
            else
            {
                $status_u = 'left';
            }
        return $status_u; 
    }

    #[Route('/{member_code}/{id}/singl', name: 'app_referral_network_new_confirm', methods: ['GET', 'POST'])]
    public function singl(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, string $member_code, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();
        $status_user = $referral_network_status[1]->getUserStatus();
        $status = $this -> status($status_user);
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        
        $arr = explode('-', $member_code);
        $listReferralNetwork_id = $arr[0];
        $pakege_user_id = $arr[1];
        $user_id = $referral_network -> getId();
        $balance = $pakege_user -> getPrice();
        
        
        //dd($balance);
        //$referral_network_status = $entityManager->getRepository(ReferralNetwork::class)->findByExampleField();

	    //dd($referral_network_status);
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $listReferralNetwork_id]);
        $network_code = $listReferralNetwork -> getNetworkCode();
        //dd($form);

        $user = $this -> getUser();    
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus($status);
        $referral_network -> setPakegeId($pakege_user_id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setBalance($balance);
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referralNetworkRepository->add($referral_network);
        
        $entityManager->persist($referral_network);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
    }

}
