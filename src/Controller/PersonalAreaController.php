<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonalAreaController extends AbstractController
{
    #[Route('/personal/area', name: 'app_personal_area')]
    public function index(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        return $this->render('personal_area/index.html.twig', [
            'controller_name' => 'Личный кабинет',
            'title' => 'Personal Area',
            'user' => $user,
        ]);
    }
}
