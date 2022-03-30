<?php

namespace App\Controller;

use App\Entity\TablePakage;
use App\Form\TablePakageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TablePakageRepository;
use Symfony\Component\HttpFoundation\Request;


class CometPakagesController extends AbstractController
{
    #[Route('/comet/pakages', name: 'app_comet_pakages', methods: ['GET'])]
    public function index(TablePakageRepository $tablePakageRepository): Response
    {
        
        return $this->render('comet_pakages/index.html.twig', [
            'controller_name' => 'Страница обзора пакетов',
            'table_pakages' => $tablePakageRepository->findAll(),
            'title' => 'Pakages',
        ]);
    }
}
