<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Entity\ListReferralNetworks;
use App\Form\ListReferralNetworksType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ListReferralNetworksRepository;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/list/referral')]
class ListReferralNetworksController extends AbstractController
{
    #[Route('/', name: 'app_list_referral_networks_index', methods: ['GET'])]
    public function index(ListReferralNetworksRepository $listReferralNetworksRepository,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        //$pakages = $referralPakegeRepository->findAll();
        $pakages = $entityManager->getRepository(Pakege::class)->findAll();
        foreach($pakages as $pakage){
            $pakage_price_all[] = $pakage -> getPrice();
        }
        $pakage_price_all_summ = array_sum($pakage_price_all);
        $pakage_count = count($pakages);
        return $this->render('list_referral_networks/index.html.twig', [
            'list_referral_networks' => $listReferralNetworksRepository->findAll(),
            'controller_name' => 'Список всех реферальных сетей',
            'title' => 'All network referral list',
            'pakage_count' => $pakage_count,
            'pakage_price_all_summ' => $pakage_price_all_summ,
        ]);
    }

    #[Route('/list', name: 'app_list_referral_networks_index_list', methods: ['GET'])]
    public function indexList(ListReferralNetworksRepository $listReferralNetworksRepository,ReferralNetworkRepository $referralNetworkRepository,ManagerRegistry $doctrine): Response
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        $referral_network_all = $referralNetworkRepository->findAll();
        $referral_network_all_count = count($referral_network_all);
        $referralNetwork = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField(['user_id' => $user_id]);
        $network_id =[];
        foreach($referralNetwork as $network){
            $network_id[] = $network -> getNetworkId();
        }
        $array = array_unique($network_id);
        
        foreach($array as $value){
            $listReferralNetworks[] = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $value]);
        }
        return $this->render('list_referral_networks/index_list.html.twig', [
            'list_referral_networks' => $listReferralNetworks,
            'controller_name' => 'Список моих реферальных сетей',
            'title' => 'My network referral list',
            'referral_network_all_count' => $referral_network_all_count,
        ]);
    }

    #[Route('/{id}/new', name: 'app_list_referral_networks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine, ListReferralNetworksRepository $listReferralNetworksRepository,int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $pakege = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $referral_link = $pakege -> getReferralLink();//она же код реферальной сети network_code

        if($referral_link == NULL){
            $listReferralNetwork = new ListReferralNetworks();
            $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
            $form->handleRequest($request);
            
            $this->addFlash(
                'info',
                'Вы перешли на страницу активации пакета без реферальной ссылки, это значит вы создаете свою сеть, в которй будете основателем');   
            if ($form->isSubmitted() && $form->isValid()) {
                
                $listReferralNetworksRepository->add($listReferralNetwork);
                return $this->redirectToRoute('app_list_referral_networks_new_confirm', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            return $this->redirectToRoute('app_referral_network_new', ['referral_link' => $referral_link, 'id' => $id], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/new.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
            'pakege_id' => $id,
        ]);
    }

    #[Route('/{id}', name: 'app_list_referral_networks_show', methods: ['GET'])]
    public function show(ListReferralNetworks $listReferralNetwork): Response
    {
        return $this->render('list_referral_networks/show.html.twig', [
            'list_referral_network' => $listReferralNetwork,
        ]);
    }

    #[Route('/{id}/list', name: 'app_list_referral_networks_show_list', methods: ['GET'])]
    public function showList(ListReferralNetworks $listReferralNetwork,ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $referral_network_all = $referralNetworkRepository->findAll();
        $referral_network_all_count = count($referral_network_all);
        return $this->render('list_referral_networks/show_list.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'referral_network_all_count' => $referral_network_all_count,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_list_referral_networks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listReferralNetworksRepository->add($listReferralNetwork);
            return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/edit.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_list_referral_networks_delete', methods: ['POST'])]
    public function delete(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$listReferralNetwork->getId(), $request->request->get('_token'))) {
            $listReferralNetworksRepository->remove($listReferralNetwork);
        }

        return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/new/confirm', name: 'app_list_referral_networks_new_confirm', methods: ['GET', 'POST'])]
    public function newConfirm(Request $request,  ManagerRegistry $doctrine, ListReferralNetworksRepository $listReferralNetworksRepository, ReferralNetworkRepository $referralNetworkRepository, int $id): Response
    {  
        $user = $this -> getUser();
        $user_id = $user -> getId();
        
        $entityManager = $doctrine->getManager();
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['pakege' => $id]);
        $pakege = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);//пакет основателя сети
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $owner_name = $user_table -> getUsername();
        $pakege_id = $pakege -> getId();//id пакета основателя сети
        //$id переданный в агрументе id пакета пользователя пришедшего для записи в качестве члена сети, в данном случае совпадает с владельцем сети

        $balance = $pakege -> getPrice();
        $client_code = $pakege -> getClientCode();
        $unique_code = $pakege -> getUniqueCode();//уникальный код сети генерировался на этапе начального создания 
        $listReferralNetwork_id = $listReferralNetwork -> getId();// id реферальной сети

        //$network_code - уникальный код реферальной сети
        $network_code = $pakege_id.'-'.$unique_code;//первая част до тире "id реферальной сети " - после тире "уникальный код сети" который одинаковый с уникальным кодом пакета unique_code

        $member_code = $listReferralNetwork_id.'-'.$id.'-'.$pakege_id.'-'.$unique_code;//инидивидуальный уникальный код записи члена реферальной сети
        $listReferralNetwork -> setOwnerId($user_id);
        $listReferralNetwork -> setOwnerName($owner_name);
        $listReferralNetwork -> setClientCode($client_code);
        $listReferralNetwork -> setNetworkCode($network_code);
        $listReferralNetwork -> setUniqueCode($unique_code);
        //$listReferralNetwork -> setProfitNetwork($balance);
        $pakege -> setActivation('активирован');
        $pakege -> setReferralNetworksId($member_code);

        //запись владельца сети в качестве - члена реферальной сети
        $referral_network = new ReferralNetwork();
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus('left');
        $referral_network -> setPakegeId($id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setUserStatus('owner');
        $referral_network -> setBalance($balance);
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referral_network -> setMyTeam($member_code);
        $referralNetworkRepository->add($referral_network);
        
        $entityManager->persist($referral_network);
        $entityManager->flush();


        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и создали новую реферальную сеть.');
        
        $listReferralNetworksRepository->add($listReferralNetwork);
        return $this->redirectToRoute('app_referral_network_show', [], Response::HTTP_SEE_OTHER);        
    }
}
