<?php

namespace App\Controller;

use App\Entity\User;
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
        
        return $this->render('personal_area/index.html.twig', [
            'controller_name' => 'Личный кабинет',
            'title' => 'Personal Area',
            'user' => $user,
        ]);
    }
}
