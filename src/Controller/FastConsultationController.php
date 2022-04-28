<?php

namespace App\Controller;

use App\Entity\FastConsultation;
use App\Controller\MailerController;
use App\Repository\SavingMailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FastConsultationController extends AbstractController
{
    #[Route('/fast/consultation', name: 'app_fast_consultation')]
    public function index(): Response
    {
        return $this->render('fast_consultation/index.html.twig', [
            'controller_name' => 'FastConsultationController',
        ]);
    }

    public function fastSendMeil(Request $request,MailerInterface $mailer, $fast_consultation, MailerController $mailerController, $entityManager, $email_client,$savingMailRepository)
    {
        $mailerController->sendFastConsultationEmail($mailer,$fast_consultation,$email_client, $savingMailRepository);
    }
}
