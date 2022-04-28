<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Repository\SavingMailRepository;
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
    public function index(Request $request, MailerInterface $mailer, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ManagerRegistry $doctrine,SavingMailRepository $savingMailRepository): Response
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
        $referral_networks = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField($user_id);
        if(count($referral_networks) > 1){
            $multi_pakage = 1;
        }
        elseif(count($referral_networks) == 1 || $referral_networks == NULL){
            $multi_pakage = 0;
        }
        
        $pakege_user = $entityManager->getRepository(Pakege::class)->findOneBy(['user_id'=>$user_id]);
        if($pakege_user != NULL){
            $pakege_id = $pakege_user -> getId();
        }
        elseif($pakege_user == NULL || $pakege_user == false){
            $pakege_id = 0;
        }
        
        if($referral_network != NULL){
            $my_team = $referral_network -> getMemberCode();
        }
        else{
            $my_team = 0;
        }
        
        if($this->getUser()->getWallet() == NULL){
            $personal_data = 0;
        }
        else{
            $personal_data = $this->getUser() -> getPersonalDataId();
        }
        $array_my_team = $entityManager->getRepository(ReferralNetwork::class)->findByMyTeamField([$my_team]);//получаем объект  участников моей команды (которых пригласил пользователь)

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_personal_area', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personal_area/index.html.twig', [
            'controller_name' => 'Личный кабинет',
            'title' => 'Personal Area',
            'user' => $user,
            'my_team' => $my_team,
            'pakege_id' => $pakege_id,
            'fast_consultation_form' => $fast_consultation_form,
            'multi_pakage' => $multi_pakage,
            'personal_data' => $personal_data,
        ]);
    }
}
