<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ReferralNetwork;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonalAreaController extends AbstractController
{
    #[Route('/personal/area', name: 'app_personal_area')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $repository = $doctrine->getRepository(User::class);

        if($repository->findOneBy(['id' => $user -> getId()]) -> getId() != $user -> getId())
        {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }
        $entityManager = $doctrine->getManager();
        $user_id = $user -> getId();
        $referral_network = $entityManager->getRepository(ReferralNetwork::class)->findOneBy(['user_id' => $user_id]);
        $my_team = $referral_network -> getMyTeam();
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь)
        return $this->render('personal_area/index.html.twig', [
            'controller_name' => 'Личный кабинет',
            'title' => 'Personal Area',
            'user' => $user,
            'my_team' => $my_team,
        ]);
    }
}
