<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 05-Jan-19
 * Time: 19:57
 */
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class MailSender
{
private $mailer;

public function __construct(\Swift_Mailer $mailer)
{
    $this->mailer=$mailer;
}

    public function posaljiKod(string $kod,string $naslov, string $odrediste){
    $message=(new \Swift_Message($naslov))->setFrom('hrvoje.sostaric96.gmail.com')->setTo($odrediste)->setBody($kod,'text/plain');
    $this->mailer->send($message);
}

}
