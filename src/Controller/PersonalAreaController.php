<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonalAreaController extends AbstractController
{
    #[Route('/personal/area', name: 'app_personal_area')]
    public function index(Request $request, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine): Response
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
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id'=>$user_id]);
        if($pakege_user != NULL){
            $pakege_id = $pakege_user -> getId();
        }
        else{
            $pakege_id = 0;
        }
        
        if($referral_network != NULL){
            $my_team = $referral_network -> getMyTeam();
        }
        else{
            $my_team = 0;
        }
        
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь)

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_area/index.html.twig', [
            'controller_name' => 'Личный кабинет',
            'title' => 'Personal Area',
            'user' => $user,
            'my_team' => $my_team,
            'pakege_id' => $pakege_id,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }
}
