<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutProjectController extends AbstractController
{
    #[Route('/about/project', name: 'app_about_project')]
    public function index(): Response
    {
        return $this->render('about_project/index.html.twig', [
            'controller_name' => 'О проекте',
            'title' => 'about of project',
        ]);
    }
}
