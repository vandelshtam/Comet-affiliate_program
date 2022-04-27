<?php

namespace App\Controller;

use App\Entity\SavingMail;
use App\Security\EmailVerifier;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use App\Repository\SavingMailRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class MailerController extends AbstractController
{
    #[Route('/mailer', name: 'app_mailer')]
    public function index(): Response
    {
        return $this->render('mailer/index.html.twig', [
            'controller_name' => 'MailerController',
        ]);
    }

    #[Route('/email')]
    public function sendEmail(MailerInterface $mailer,SavingMailRepository $savingMailRepository)
    {
        $user = $this -> getUser();
        $email_user = $user -> getEmail();
        $user_id = $user -> getId();
        $saving_mail = new SavingMail();
        $email = (new TemplatedEmail())
            ->from('Commet-AT@example.com')
            ->to($email_user)
            ->subject('Time for Symfony Mailer!')
            ->text('Благодарим Вас за вступление в нашу сеть и приобретение пакета! Перейти на сайт ,<a href="http://164.92.159.123">ссылка на сайт</a>')
            ->htmlTemplate('emails/pakage_new.html.twig')
            ->context([
                 'date' => new \DateTime(),
            ]);
            $saving_mail -> setCategory('new_pakege');
            $saving_mail -> setUserId($user_id);
            $saving_mail -> setCreatedAt((new \DateTime()));
            $saving_mail -> setUpdatedAt((new \DateTime()));

            try {
                $mailer->send($email);
                $this->addFlash(
                'info',
                'Вам отправлено электронное письмо с подтверждением вступления в CoMetaClub!'); 
                $saving_mail -> setStatus('success');
                $saving_mail -> setText('Благодарим Вас за вступление в нашу сеть и приобретение пакета!');
                $savingMailRepository -> add($saving_mail);
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'danger',
                    'Произошел не предвиденный сбой почтового клиента, электронное письмо с подтверждением вступления в CoMetaClub не отправлено. Приносим извинения, мы обязательно отправим вам письмо с подтверждение в ближайшее время'); 
                $saving_mail -> setStatus('error');
                $saving_mail -> setText('Произошел не предвиденный сбой почтового клиента, электронное письмо с подтверждением вступления в CoMetaClub не отправлено. Приносим извинения, мы обязательно отправим вам письмо с подтверждение в ближайшее время');
                $savingMailRepository -> add($saving_mail);    
            }             

    }

    public function sendFastConsultationEmail(MailerInterface $mailer,$fast_consultation,$email_client, $savingMailRepository)
    {
        $user = $this -> getUser();
        $user_id = $user -> getId();
        $saving_mail = new SavingMail();
        $email = (new TemplatedEmail())
            ->from($email_client)
            ->to('helfopets@gmail.com')
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('emails/fast_consultation.html.twig')
            ->context([
                'username' => $fast_consultation->getName(),
                'date' => new \DateTime(),
                'question' => $fast_consultation->getQuestion(),
                'phone' => $fast_consultation->getPhone(),
                'post_mail' => $fast_consultation->getEmail(),
            ]);
            $saving_mail -> setCategory('technical_support');
            $saving_mail -> setUserId($user_id);
            $saving_mail -> setToMail('Commet-AT@example.com');
            $saving_mail -> setFromMail($email_client);
            $saving_mail -> setText($fast_consultation->getQuestion());
            $saving_mail -> setCreatedAt((new \DateTime()));
            $saving_mail -> setUpdatedAt((new \DateTime()));
            try {
                $mailer->send($email);
                $this->addFlash(
                'success',
                'Вы успешно отправили  свой вопрос, скоро мы свяжемся с вами!'); 
                $saving_mail -> setStatus('success');
                $savingMailRepository -> add($saving_mail);    
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'success',
                    'Произошел не предвиденный сбой почтового клиента, не удалось отправить ссылку, чуть позже попробуйте еще раз.');
                $saving_mail -> setStatus('error');
                $savingMailRepository -> add($saving_mail);     
            }             
    }

    public function sendReferralToEmail(MailerInterface $mailer,$email_to_client,$email_user,$referral_link,$personal_data_username,$savingMailRepository,)
    {
        $user = $this -> getUser();
        $email_user = $user -> getEmail();
        $user_id = $user -> getId();
        $saving_mail = new SavingMail();
        $email = (new TemplatedEmail())
            ->from($email_user)
            ->to($email_to_client)
            ->subject('Time for Symfony Mailer!')
            ->htmlTemplate('emails/initiation.html.twig')
            ->context([
                'username' => $personal_data_username,
                'date' => new \DateTime(),
                'referral_link' => $referral_link,
            ]);
            $saving_mail -> setCategory('referral_link');
            $saving_mail -> setUserId($user_id);
            $saving_mail -> setToMail($email_to_client);
            $saving_mail -> setFromMail($email_user);
            $saving_mail -> setText('http://164.92.159.123/register/'.$referral_link);
            $saving_mail -> setCreatedAt((new \DateTime()));
            $saving_mail -> setUpdatedAt((new \DateTime()));
            try {
                $mailer->send($email);
                $this->addFlash(
                'success',
                'Вы успешно отправлили реферальную ссылку новому кандидату!');
                $saving_mail -> setStatus('success');
                $savingMailRepository -> add($saving_mail);     
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'success',
                    'Произошел не предвиденный сбой почтового клиента, не удалось отправить ссылку, чуть позже попробуйте еще раз.');
                $saving_mail -> setStatus('error');
                $savingMailRepository -> add($saving_mail);         
            }             
    }
}
