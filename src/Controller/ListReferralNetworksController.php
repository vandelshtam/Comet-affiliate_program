<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Entity\ListReferralNetworks;
use App\Form\ListReferralNetworksType;
use App\Repository\SavingMailRepository;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ListReferralNetworksRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[Route('/list/referral')]
class ListReferralNetworksController extends AbstractController
{
    #[Route('/admin', name: 'app_list_referral_networks_index', methods: ['GET'])]
    public function index(Request $request, ListReferralNetworksRepository $listReferralNetworksRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        //$pakages = $referralPakegeRepository->findAll();
        $pakages = $entityManager->getRepository(Pakege::class)->findAll();
        foreach($pakages as $pakage){
            $pakage_price_all[] = $pakage -> getPrice();
        }
        $pakage_price_all_summ = array_sum($pakage_price_all);
        $pakage_count = count($pakages);

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('list_referral_networks/index.html.twig', [
            'list_referral_networks' => $listReferralNetworksRepository->findAll(),
            'controller_name' => 'Информация о реферральной сети',
            'title' => 'All network referral list',
            'pakage_count' => $pakage_count,
            'pakage_price_all_summ' => $pakage_price_all_summ,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/list/admin', name: 'app_list_referral_networks_index_list', methods: ['GET'])]
    public function indexList(Request $request, ListReferralNetworksRepository $listReferralNetworksRepository,ReferralNetworkRepository $referralNetworkRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        $referral_network_all = $referralNetworkRepository->findAll();
        $referral_network_all_count = count($referral_network_all);
        $referralNetwork = $entityManager->getRepository(ReferralNetwork::class)->findByUserIdField(['user_id' => $user_id]);
        $network_id =[];
        foreach($referralNetwork as $network){
            $network_id[] = $network -> getNetworkId();
        }
        $array = array_unique($network_id);
        
        foreach($array as $value){
            $listReferralNetworks[] = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $value]);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('list_referral_networks/index_list.html.twig', [
            'list_referral_networks' => $listReferralNetworks,
            'controller_name' => 'Список моих реферальных сетей',
            'title' => 'My network referral list',
            'referral_network_all_count' => $referral_network_all_count,
            'fast_consultation_form' => $fast_consultation_form->createView(),
        ]);
    }

    #[Route('/{id}/new/admin', name: 'app_list_referral_networks_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, ListReferralNetworksRepository $listReferralNetworksRepository, SavingMailRepository $savingMailRepository,int $id): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        
        if($entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]) == false){
            $this->addFlash(
                'warning',
                'Нет такого пользователя');
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        $pakege = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        if($user_id != $pakege -> getUserId()){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
        }

        $referral_link = $pakege -> getReferralLink();//она же код реферальной сети network_code

        if($referral_link == NULL){
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $listReferralNetwork = new ListReferralNetworks();
            $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
            $form->handleRequest($request);
            
            $this->addFlash(
                'info',
                'Вы перешли на страницу активации пакета без реферальной ссылки, это значит вы создаете свою сеть, в которй будете основателем');   
            if ($form->isSubmitted() && $form->isValid()) {
                
                $listReferralNetworksRepository->add($listReferralNetwork);
                return $this->redirectToRoute('app_list_referral_networks_new_confirm', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
        }
        else{
            
            return $this->redirectToRoute('app_referral_network_new', ['referral_link' => $referral_link, 'id' => $id], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/new.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
            'pakege_id' => $id,
            'fast_consultation_form' => $fast_consultation_form,
            'controller_name' => 'Новая реферальная сеть',
            'title' => 'New network referral list',
        ]);
    }

    #[Route('/{id}/admin', name: 'app_list_referral_networks_show', methods: ['GET'])]
    public function show(Request $request,ListReferralNetworks $listReferralNetwork,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('list_referral_networks/show.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Реферральная сеть',
            'title' => 'network referral list show',
        ]);
    }

    #[Route('/{id}/list/admin', name: 'app_list_referral_networks_show_list', methods: ['GET'])]
    public function showList(Request $request,ListReferralNetworks $listReferralNetwork,ReferralNetworkRepository $referralNetworkRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client,$savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $referral_network_all = $referralNetworkRepository->findAll();
        $referral_network_all_count = count($referral_network_all);
        return $this->render('list_referral_networks/show_list.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'referral_network_all_count' => $referral_network_all_count,
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'controller_name' => 'Информация о реферральной сети',
            'title' => 'All network referral list',
        ]);
    }

    #[Route('/{id}/edit/admin', name: 'app_list_referral_networks_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,SavingMailRepository $savingMailRepository, int $id): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $doctrine->getManager();
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['id' => $id]);
        
        $pakege_id = $listReferralNetwork -> getPakege() -> getId();

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$email_client, $savingMailRepository); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(ListReferralNetworksType::class, $listReferralNetwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $listReferralNetworksRepository->add($listReferralNetwork);
            $listReferralNetworksRepository -> setUpdatedAt(new \DateTime());
            $entityManager->flush();
            return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('list_referral_networks/edit.html.twig', [
            'list_referral_network' => $listReferralNetwork,
            'form' => $form,
            'fast_consultation_form' => $fast_consultation_form,
            'pakege_id' => $pakege_id,
            'controller_name' => 'Редактирование реферральной сети',
            'title' => 'edit network referral list',
        ]);
    }

    #[Route('/{id}/admin', name: 'app_list_referral_networks_delete', methods: ['POST'])]
    public function delete(Request $request, ListReferralNetworks $listReferralNetwork, ListReferralNetworksRepository $listReferralNetworksRepository): Response
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$listReferralNetwork->getId(), $request->request->get('_token'))) {
            $listReferralNetworksRepository->remove($listReferralNetwork);
        }

        return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}/new/confirm/admin', name: 'app_list_referral_networks_new_confirm', methods: ['GET', 'POST'])]
    public function newConfirm(Request $request,  ManagerRegistry $doctrine, ListReferralNetworksRepository $listReferralNetworksRepository, ReferralNetworkRepository $referralNetworkRepository,SavingMailRepository $savingMailRepository, int $id): Response
    {  
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $user = $this -> getUser();
        $user_id = $user -> getId();
        
        $entityManager = $doctrine->getManager();
        $listReferralNetwork = $entityManager->getRepository(ListReferralNetworks::class)->findOneBy(['pakege' => $id]);
        $pakege = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);//пакет основателя сети
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        //$wallet_table = $entityManager->getRepository(Wallet::class)->findOneBy(['id' => $user_id]);
        $owner_name = $user_table -> getUsername();
        $pakege_id = $pakege -> getId();//id пакета основателя сети
        //$id переданный в агрументе id пакета пользователя пришедшего для записи в качестве члена сети, в данном случае совпадает с владельцем сети

        $balance = $pakege -> getPrice();
        //$current_wallet_usdt = $wallet_table -> getUsdt();
        $client_code = $pakege -> getClientCode();
        $unique_code = $pakege -> getUniqueCode();//уникальный код сети генерировался на этапе начального создания 
        $listReferralNetwork_id = $listReferralNetwork -> getId();// id реферальной сети

        //$network_code - уникальный код реферальной сети
        $network_code = $pakege_id.'-'.$unique_code;//первая част до тире "id реферальной сети " - после тире "уникальный код сети" который одинаковый с уникальным кодом пакета unique_code

        $date = new \DateTime();
        
        $member_code = $listReferralNetwork_id.'-'.$id.'-'.$pakege_id.'-'.$unique_code;//инидивидуальный уникальный код записи члена реферальной сети
        $listReferralNetwork -> setOwnerId($user_id);
        $listReferralNetwork -> setOwnerName($owner_name);
        $listReferralNetwork -> setClientCode($client_code);
        $listReferralNetwork -> setNetworkCode($network_code);
        $listReferralNetwork -> setUniqueCode($unique_code);
        $listReferralNetwork -> setProfitNetwork(0);//общая сумма очислений в проект (владельцам проекта)
        $listReferralNetwork -> setPaymentsDirect(0);//общая сумма начисленных доходов в сеть по программе Директ
        $listReferralNetwork -> setPaymentsCash(0);//общая сумма начисленных в сети доходов по программе КешБек
        $listReferralNetwork -> setSystemRevenues(0);//общая сумма начисленных  доходов в систему (30%)
        $listReferralNetwork -> setCurrentBalance(0);//общая сумма стоимости пакетов в сети (не погашенных)
        $listReferralNetwork -> setCreatedAt($date);
        
        //$user_table -> setPakageStatus(1);
        //$listReferralNetwork -> setProfitNetwork($balance);
        $pakege -> setActivation('активирован');
        $pakege -> setReferralNetworksId($member_code);

        //запись владельца сети в качестве - члена реферальной сети
        $referral_network = new ReferralNetwork();
        $referral_network -> setUserId($user_id);
        $referral_network -> setUserStatus('left');
        $referral_network -> setPakegeId($id);
        $referral_network -> setNetworkId($listReferralNetwork_id);
        $referral_network -> setUserStatus('owner');
        $referral_network -> setBalance($balance);
        $referral_network -> setPakage($balance);
        $referral_network -> setNetworkCode($network_code);
        $referral_network -> setMemberCode($member_code);//первая часть до первого тире "id пакета приглашенного участника сети (т.е. id пакета приглашенного )" -  вторая часть перед вторым тире, "id пакета владельца сети (т.е. id пакета)" - после тире "уникальный код сети" 
        $referral_network -> setMyTeam($member_code);

        $referral_network -> setReward(0);//сумма начислений дохода пользователя в сети
        $referral_network -> setCash(0);//сумма начисления  дохода пользователю  по системе КэшБек
        $referral_network -> setDirect(0);//сумма начислений в сети пользователю по программе Директ
        $referral_network -> setCurrentNetworkProfit(0);//текщее отчисление  в доход проекта от погашения пакетов в момент активации пакета нвого пользователя
        $referral_network -> setPaymentsNetwork(0);//начисление в сеть по программе Директ в момент активации нового пакета
        $referral_network -> setPaymentsCash(0);//начисление в сеть по программе КешБек в момент активации нового пакета 
        $referral_network -> setRewardWallet(0);//остаток начислений доступных для вывода на кошелек пользователя
        $referral_network -> setWithdrawalToWallet(0);//общая сумма выведенных на кошелек начисленых доходов пользователя
        $listReferralNetwork -> setSystemRevenues(0);//общая сумма начисленных  доходов в систему (30%)
        $referral_network -> setCreatedAt($date);

        $referralNetworkRepository->add($referral_network);
        
        $entityManager->persist($referral_network);
        $entityManager->flush();


        $this->addFlash(
            'success',
            'Поздравляем! Вы успешно активировали пакет и создали новую реферальную сеть.');
        
        $listReferralNetworksRepository->add($listReferralNetwork);
        return $this->redirectToRoute('app_list_referral_networks_index', [], Response::HTTP_SEE_OTHER);        
    }
}
