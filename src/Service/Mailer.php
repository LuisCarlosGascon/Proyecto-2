<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\NamedAddress;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class Mailer
{
    private $mailer;
    private $subject;
    private $text;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->subject='Prueba';
        $this->text='Este correo es de prueba';
    }

    public function sendEmail(User $user)
    {
        $email = (new Email())
            ->from('lcgascon221@gmail.com')
            ->to($user->getEmail())
            ->subject($this->subject)
            ->text($this->text);
        $this->mailer->send($email);
    }

    public function setSubject(String $string){
        $this->subject=$string;
    }

    public function setText(String $string){
        $this->text=$string;
    }
}