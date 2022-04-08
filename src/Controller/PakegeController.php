<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Pakege;
use App\Entity\Wallet;
use App\Form\PakegeType;
use App\Entity\TokenRate;
use App\Entity\TablePakage;
use App\Entity\PersonalData;
use App\Entity\ReferralNetwork;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Controller\MailerController;
use App\Repository\PakegeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReferralNetworkRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/pakege')]
class PakegeController extends AbstractController
{
    #[Route('/', name: 'app_pakege_index', methods: ['GET'])]
    public function index(PakegeRepository $pakegeRepository, Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
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

        return $this->render('pakege/index.html.twig', [
            'pakeges' => $pakegeRepository->findAll(),
            'controller_name' => 'Список всех приобретенных пакетов в сети',
            'title' => 'Pakages',
            'fast_consultation_form' => $fast_consultation_form -> createView(),
        ]);
    }

    #[Route('/new', name: 'app_pakege_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PakegeRepository $pakegeRepository,EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController,ReferralNetworkRepository $referralNetworkRepository): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');

        $user = $this -> getUser();
        $user_id = $user -> getId();
        //проверка баланса кошелька для покупки нового пакета
        $wallet = $entityManager->getRepository(Wallet::class)->findOneBySomeField($user_id);

        //dd($user_id);
        if( $wallet == NULL){
            $this->addFlash(
                'warning',
                'Вы не пополнили кошелек, у вас нет средств на балансе, перейти к пополнению' .'<a href="http://164.92.159.123/wallet/new">пополнить?</a>'. 'oo');
            return $this->redirectToRoute('app_wallet_user', [], Response::HTTP_SEE_OTHER);
        }
        
        $pakege = new Pakege();
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        
        $user = $this -> getUser();
        $user_referral_link = $user -> getReferralLink();
        
        $entityManager = $doctrine->getManager();
        
        $unique_code1 = $this->random_string(10);
        $unique_code2 = $this->random_string(10);
        $unique_code = $unique_code1.$unique_code2;
        $collection = new ArrayCollection();
        $collection -> add($unique_code);

        if ($form->isSubmitted() && $form->isValid()) {
            $form_referral_link = $form->get('referral_link')->getData();
            $form_pakage_name = $form->get('name')->getData();
            $form_referral_select = $request->get('select');
            //dd($form_pakage_name);
            $pakage_user = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $form_pakage_name]); 
            $token_rate = $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();
            $wallet_cometpoin = $wallet -> getCometpoin();
            $wallet_cometcoin_rate =  $wallet_cometpoin / $token_rate;
            $pakage_user_price = $pakage_user -> getPricePakage();
            if($form_referral_select == 1){

                if(($wallet -> getUsdt()) < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            elseif($form_referral_select == 2){
                if($wallet_cometcoin_rate < $pakage_user_price ){
                    $this->addFlash(
                        'warning',
                        'У вас недостаточно средств для приобретения пакета, пополните кошелек');
                    return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            //dd($form_referral_select);
            if($form_referral_link != NULL){
                if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $form_referral_link]) == false ){
                
                $this->addFlash(
                    'danger',
                    'Вы ошиблись при введении ссылки или ввели устаревшую ссылку, пожалуйста попробуйте еще раз');
                return $this->redirectToRoute('app_pakege_new', [], Response::HTTP_SEE_OTHER);
                }
            }
            elseif($form_referral_link == NULL){
                $this->denyAccessUnlessGranted('ROLE_ADMIN'); 
            }
            
                
            $pakegeRepository->add($pakege);
            $unique = $form->get('unique_code')->getData();

            $pakage_comet_id = $this -> newChoice ($request,$pakegeRepository,$doctrine,$unique,$wallet,$form_referral_select,$form_pakage_name,$pakage_user_price,$token_rate);
            $entityManager->flush();
            $mailerController->sendEmail($mailer);
            $this->addFlash(
                'success',
                'Вы успешно приобрели новый пакет, на электронную почту отправлено подтверждение операции');
            $this->addFlash(
                'info',
                'Чтобы пакет начал работать вы должны активировать пакет!'); 
                
            return $this->redirectToRoute('app_pakege_show', ['id' => $pakage_comet_id], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/new.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $collection[0],
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_show', methods: ['GET'])]
    public function show(Pakege $pakege,Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController): Response
    {
        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pakege/show.html.twig', [
            'pakege' => $pakege,
            'fast_consultation_form' => $fast_consultation_form -> createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pakege_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository, EntityManagerInterface $entityManager, MailerInterface $mailer,ManagerRegistry $doctrine, FastConsultationController $fast_consultation_meil, MailerController $mailerController, int $id): Response
    {
        $form = $this->createForm(PakegeType::class, $pakege);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['id' => $id]);
        $unique = $pakage_comet -> getUniqueCode();
        $user_referral_link = $pakage_comet -> getReferralLink();

        if ($form->isSubmitted() && $form->isValid()) {
            $pakegeRepository->add($pakege);
            return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pakege/edit.html.twig', [
            'pakege' => $pakege,
            'form' => $form,
            'unique_code' => $unique,
            'user_referral_link' => $user_referral_link,
            'fast_consultation_form' => $fast_consultation_form,
        ]);
    }

    #[Route('/{id}', name: 'app_pakege_delete', methods: ['POST'])]
    public function delete(Request $request, Pakege $pakege, PakegeRepository $pakegeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pakege->getId(), $request->request->get('_token'))) {
            $pakegeRepository->remove($pakege);
            $this->addFlash(
                'danger',
                'Вы успешно удалили пакет, на электронную почту отправлено подтверждение операции');
        }

        return $this->redirectToRoute('app_pakege_index', [], Response::HTTP_SEE_OTHER);
    }

    //#[Route('/new/{unique}/choice', name: 'app_pakege_new_choice', methods: ['GET', 'POST'])]
    private function newChoice ($request, $pakegeRepository, $doctrine, $unique,$wallet,$form_referral_select,$form_pakage_name,$pakage_user_price,$token_rate)
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $entityManager = $doctrine->getManager();
        $user_table = $entityManager->getRepository(User::class)->findOneBy(['id' => $user_id]);
        $personal_data_table = $entityManager->getRepository(PersonalData::class)->findOneBy(['user_id' => $user -> getId()]);
        $client_code = $personal_data_table -> getClientCode();
        $pakage_comet = $entityManager->getRepository(Pakege::class)->findOneBy(['unique_code' => $unique]);
        $pakage_comet_name = $pakage_comet -> getName();
        $pakage_comet_id = $pakage_comet -> getId();

        if($form_referral_select == 1){
            $current_usdt = $wallet -> getUsdt();
            $new_balance_usdt = $current_usdt - $pakage_user_price;
            //dd($new_balance_usdt);
            $wallet -> setUsdt($new_balance_usdt);
        }
        elseif($form_referral_select == 2){
            $current_cometpoin = $wallet -> getCometpoin();
            $new_balance_cometpoin = $current_cometpoin - ($pakage_user_price * $token_rate);
            $wallet -> setCometpoin($new_balance_cometpoin);
        }
            
        $pakage_table = $entityManager->getRepository(TablePakage::class)->findOneBy(['name' => $pakage_comet_name]);
        $token_table =  $entityManager->getRepository(TokenRate::class)->findOneBy(['id' => 1]) -> getExchangeRate();

        $price_usdt = $pakage_table -> getPricePakage();
        $price_token = $price_usdt * $token_table;
         
        $user_table -> setPakageStatus(1);
        $pakage_comet -> setUserId($user_id);
        $pakage_comet -> setPrice($price_usdt);
        $pakage_comet -> setToken($price_token);
        $pakage_comet -> setClientCode($client_code);
         
        return $pakage_comet_id;
    }



    public function random_string ($str_length)
    {
    $str_characters = array (0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

	// Функция может генерировать случайную строку и с использованием кириллицы
    //$str_characters = array (0,1,2,3,4,5,6,7,8,9,'а','б','в','г','д','е','ж','з','и','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','э','ю','я');

    // Возвращаем ложь, если первый параметр равен нулю или не является целым числом
    if (!is_int($str_length) || $str_length < 0)
    {
        return false;
    }

    // Подсчитываем реальное количество символов, участвующих в формировании случайной строки и вычитаем 1
    $characters_length = count($str_characters) - 1;

    // Объявляем переменную для хранения итогового результата
    $string = '';

    // Формируем случайную строку в цикле
    for ($i = $str_length; $i > 0; $i--)
    {
        $string .= $str_characters[mt_rand(0, $characters_length)];
    }

    // Возвращаем результат
    return $string;
    }
}
