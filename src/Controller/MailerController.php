<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
    public function sendEmail(MailerInterface $mailer)
    {
        $user = $this -> getUser();
        $email_user = $user -> getEmail();
        $email = (new Email())
            ->from('Commet-AT@example.com')
            ->to($email_user)
            ->subject('Time for Symfony Mailer!')
            ->text('Благодарим Вас за вступление в нашу сеть и приобретение пакета! Перейти на сайт ,<a href="http://164.92.159.123">ссылка на сайт</a>')
            ->html('<h1>Здравствуйте, мы благодарим вас за участие в нашей сети и приоретение пакета участника реферальной сети!</h1>
            <p>Теперь Вам необходимо активировать свой пакет участника </p> <a href="http://164.92.159.123">ссылка на сайт</a>');
            try {
                $mailer->send($email);
                $this->addFlash(
                'info',
                'Вам отправлено электронное письмо!');     
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'danger',
                    'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
            }             

    }

    public function sendFastConsultationEmail(MailerInterface $mailer,$fast_consultation,$textSendMail,$email_client)
    {
        $email = (new Email())
            ->from($email_client)
            ->to('Commet-AT@example.com')
            ->subject('Time for Symfony Mailer!')
            //->text($fast_consultation->getName())
            ->html($textSendMail)
            //->htmlTemplate('emails/fast_consultation.html.twig')
            ;
            try {
                $mailer->send($email);
                $this->addFlash(
                'success',
                'Вы успешно отправлили заявку, скоро мы свяжемся с вами!');     
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'success',
                    'Не удалось отправить заявку, пожалуйста попробуйте еще раз'); 
            }             
    }

    public function sendReferralToEmail($mailer,$email_to_client,$email_user,$referral_link)
    {//dd($email_user);
        $email = (new Email())
        
            ->from($email_user)
            ->to($email_to_client)
            ->subject('Time for Symfony Mailer!')
            ->text('Vld')
            ->html('<div class="card">
                        <div class="card-header" style="background: #5eabeb; color: white;">
                        <h3>Это сообщения от участника системы Single Line</h3>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">Здравствуйте, я приглашаю вас вступить в Singl Line в мою команду!</h4>
                            <p class="card-text fw-bolder">Реферальная ссылка для регистрации</p><span>' .$referral_link.'</span>
                            <p class="card-text">Eсли вы не зарегистрированы, то можете перейти на страницу регистрации. Если вы уже зарегистрированы в системе, то можете скопировать ссылку и вставить ее в поле при покупке пакета.</p>
                            <a href="http://164.92.159.123/register/$referral_link" class="btn btn-primary rounded-0" style="background: #5eabeb; hight: 100px; color: white; underline: none;">Перейти к регистрации</a>
                        </div>
                    </div>');
            try {
                $mailer->send($email);
                $this->addFlash(
                'success',
                'Вы успешно отправлили реферальную ссылку!');     
            } catch (TransportExceptionInterface $e) {
                $this->addFlash(
                    'success',
                    'Не удалось отправить ссылку попробуйте еще раз'); 
            }             
    }

    public function textEmalPakege(){
        $textEmailPakege = 
            '<h1>Здравствуйте, мы благодарим вас за участие в нашей сети и приоретение пакета участника реферальной сети!</h1>
            <p>Теперь Вам необходимо активировать свой пакет участника </p> <a href="http://164.92.159.123">ссылка на сайт</a>';
        return $textEmailPakege;
    }

    public function textFastConsultationMail($fast_consultation){
        $textFastConsultation = 
            '<h1>Здравствуйте, я '.$fast_consultation->getName().' пожалуйста ответьте на мой вопрос!</h1>
            <p>Прошу связаться со мной по номеру телефона '.$fast_consultation->getPhone().'</p>
            <p>или по электронной почте '.$fast_consultation->getEmail().'</p>';
        return $textFastConsultation;
    }
    // public function textReferralToEmailMail($referral_link){
    //     $textReferralEmail = 
    //         '<h1>Здравствуйте, я приглашаю вас вступить в Singl Line в мою команду!</h1>
    //         <p>реферальная ссылка для регистрации </p>
    //         <p>ссылка на сайт <a href="http://164.92.159.123/register">ссылка на сайт</a></p>';
    //     return $textReferralEmail;
    // }

    public function textReferralToEmailMail(){
        $textReferralEmail = 
        '<h1>Здравствуйте, мы благодарим вас за участие в нашей сети и приоретение пакета участника реферальной сети!</h1>
        <p>Теперь Вам необходимо активировать свой пакет участника </p> <a href="http://164.92.159.123">ссылка на сайт</a>';
        return $textReferralEmail;
    }
}
