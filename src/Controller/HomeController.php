<?php

namespace App\Controller;

use App\Entity\SettingOptions;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\SavingMailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        $setting = $entityManager->getRepository(SettingOptions::class)->findOneBy(['id' => 1]);
        $start_day = round($setting -> getStartDay() / 2);
        // $datetime1 = (new \DateTime()); //Получаем текущую дату
        // $datetime2 = new \DateTime($start_day.'days'); //Дата акции

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('home/index.html.twig', [
            'controller_name' => 'Главная страница',
            'fast_consultation_form' => $fast_consultation_form,
            'start_day' => $start_day,
            'user' => $this->getUser(),
        ]);
    }
}
