<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 06-Jan-19
 * Time: 18:41
 */

namespace App\Controller;


use App\Entity\Korisnik;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER");
 */
class PersonalAccountController extends AbstractController
{


    /**
     * @Route("/profil/{id}", name="user_personal");
     */
    public function perosnalAccount($id){
        $logiraniKorisnik=$this->getDoctrine()->getManager()->getRepository(Korisnik::class)->findOneBy(['id'=>$id]);

        return $this->render("personal/personalpage.html.twig",[
            'user'=>$logiraniKorisnik,
        ]);
    }
}
