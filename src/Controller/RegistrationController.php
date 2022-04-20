<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\ReferralNetwork;
use App\Security\EmailVerifier;
use App\Entity\FastConsultation;
use App\Form\FastConsultationType;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Controller\MailerController;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use App\Controller\FastConsultationController;
use Doctrine\ORM\Cache\TimestampRegion;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register/{referral?}', name: 'app_register')]
    public function register(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager,FastConsultationController $fast_consultation_meil, MailerController $mailerController,MailerInterface $mailer, ?string $referral): Response
    {
        
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

       
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($request->get('password'));
            if($request->get('password') != $form->get('plainPassword')->getData()){
                $this->addFlash(
                    'danger',
                    'Пароль и его подтверждение не совпадают, введите еще раз.'); 
                return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);      
            }
            $referral_link = $form->get('referral_link')->getData();
            $entityManager = $doctrine->getManager();
            if($referral_link != NULL){
                if($entityManager->getRepository(ReferralNetwork::class)->findOneBy(['member_code' => $referral_link]) == false){
                    $this->addFlash(
                        'danger',
                        'Вы ошиблись при вводе реферальной ссылки или ввели устаревшую ссылку, пожалуйста поробуйте еще раз или обратитесь за новой ссылкой'); 
                    return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);       
                }
            }
            
            
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            // $roles[] = 'ROLE_ADMIN';
            $user->setCreatedAt(new \DateTime());
            $user->setReferralLink($referral_link);
            $user->setPakageStatus(0);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Вы успешно прошли регистрацию, перейдите в указанную вами электронную почту и пройдите верификацию');
            $this->addFlash(
                'info',
                'Чтобы совершать действия в системе Вам необходимо зарегистрировать персональные данные!');     

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('mailer@comet-ap.com', 'Comet-ap Mail Bot'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        $fast_consultation = new FastConsultation();       
        $fast_consultation_form = $this->createForm(FastConsultationType::class,$fast_consultation);
        $fast_consultation_form->handleRequest($request);
        if ($fast_consultation_form->isSubmitted() && $fast_consultation_form->isValid()) {
            $email_client = $fast_consultation_form -> get('email')->getData(); 
            $textSendMail = $mailerController->textFastConsultationMail($fast_consultation);
            $fast_consultation_meil -> fastSendMeil($request,$mailer,$fast_consultation,$mailerController,$entityManager,$textSendMail,$email_client); 
            return $this->redirectToRoute('app_register', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'fast_consultation_form' => $fast_consultation_form->createView(),
            'referral_link' => $referral,
            'date' => $date,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_home');
    }
}
