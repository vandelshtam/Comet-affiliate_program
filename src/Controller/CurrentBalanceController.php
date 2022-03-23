<?php

namespace App\Controller;

use App\Entity\TokenRate;
use App\Entity\ReferralNetwork;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use App\Repository\TokenRateRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CurrentBalanceController extends AbstractController
{
    #[Route('/current/balance', name: 'app_current_balance')]
    public function index(ReferralNetworkRepository $referralNetworkRepository, ManagerRegistry $doctrine, TokenRateRepository $tokenRateRepository): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this -> getUser();
        $id_user = $user -> getId();
        $referral_network_user = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField(['user_id' => $id_user]);//объект пользователя
        foreach($referral_network_user as $network){
            $array_rewards[] = $network -> getBalance();
        } 
        $reward = array_sum($array_rewards);
        

        $token_rate = $tokenRateRepository->findAll();
        foreach($token_rate as $rate){
            $token_exange = $rate -> getExchangeRate();
        }
        $token = $reward * $token_exange;
        

        return $this->render('current_balance/index.html.twig', [
            'controller_name' => 'Страница просмотра текущего баланса пользователя',
            'reward' => $reward,
            'title' => 'Pakages',
            'token' => $token,
        ]);
    }
}
