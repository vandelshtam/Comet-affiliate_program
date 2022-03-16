<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CometPakagesController extends AbstractController
{
    #[Route('/comet/pakages', name: 'app_comet_pakages')]
    public function index(): Response
    {
        
        return $this->render('comet_pakages/index.html.twig', [
            'controller_name' => 'Страница представления пакетов',
            'title' => 'Pakages',
        ]);
    }
}
