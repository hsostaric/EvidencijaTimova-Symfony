<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 06-Jan-19
 * Time: 18:41
 */

namespace App\Controller;


use App\Entity\Korisnik;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PersonalAccountController extends AbstractController
{
    public function perosnalAccount(Korisnik $korisnik){
        return $this->render("",$korisnik);
    }
}
