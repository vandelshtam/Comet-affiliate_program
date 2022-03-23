<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
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

    public function textEmalPakege(){
        $textEmailPakege = 
            '<h1>Здравствуйте, мы благодарим вас за участие в нашей сети и приоретение пакета участника реферальной сети!</h1>
            <p>Теперь Вам необходимо активировать свой пакет участника </p> <a href="http://164.92.159.123">ссылка на сайт</a>';
        return $textEmailPakege;
    }
}
