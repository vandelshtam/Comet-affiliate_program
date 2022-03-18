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
    public function index(ReferralNetworkRepository $referralNetworkRepository): Response
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
        $arr = explode('-', $referral_link);//уникальный персональный код участника сети со статусом владелец сети
        //$arr[0] - id сети
        //$id пакета нового участника сети
        //$arr[2]id пакета владельца сети
        //$arr[3] 
        $member_code = $arr[0].'-'.$id.'-'.$arr[2].'-'.$arr[3];//уникальный персональный код участника сети
        //dd($member_code);

        $referralNetwork = new ReferralNetwork();
        $form = $this->createForm(ReferralNetworkType::class, $referralNetwork);
        $form->handleRequest($request);

         $user = $this -> getUser();
         $username = $user -> getUsername();
        
        if ($form->isSubmitted() && $form->isValid()) {

            $referralNetworkRepository->add($referralNetwork);
            return $this->redirectToRoute('app_referral_network_new_confirm', ['member_code' => $member_code, 'id' => $id], Response::HTTP_SEE_OTHER);
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

    #[Route('/{member_code}/{id}/new/confirm', name: 'app_referral_network_new_confirm', methods: ['GET', 'POST'])]
    public function newConfirm(Request $request, ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, string $member_code, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $member_code]);
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        
        $arr = explode('-', $member_code);
        $listReferralNetwork_id = $arr[0];
        $pakege_user_id = $arr[1];
        $user_id = $referral_network -> getId();
        $balance = $pakege_user -> getPrice();
        $status = 'left';
        
	    //dd($arr);
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
        // $referralNetworkRepository->add($referral_network);
        
        $entityManager->persist($referral_network);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и вступили в  реферальную сеть.');
            
        return $this->redirectToRoute('app_referral_network_index', [], Response::HTTP_SEE_OTHER);
        
    }
}
