<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 06-Jan-19
 * Time: 18:41
 */

namespace App\Controller;


use App\Entity\Korisnik;
use App\Repository\KorisnikRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_USER");
 */
class PersonalAccountController extends AbstractController
{

    /**
     * @Route("/profil/{id}", name="user_personal");
     */
    public function perosnalAccount($id,KorisnikRepository $korisnikRepository,Request $request,UserPasswordEncoderInterface $encoder){
        $greske=array();
        $logiraniKorisnik = $korisnikRepository->findOneBy(['id'=>$id]);
        if($request->isMethod('POST')){

            if($encoder->isPasswordValid($logiraniKorisnik,$request->request->get('staraLozinka'))){
                if ($this->provjeraNovihLozinki($request->request->get('novaLozinka'),$request->request->get('ponovljenaLozinka'))){
                        $em=$this->getDoctrine()->getManager();
                        $logiraniKorisnik->setPassword($encoder->encodePassword($logiraniKorisnik,$request->request->get('novaLozinka')));
                        $logiraniKorisnik->setLozinkaBackUp($request->request->get('novaLozinka'));
                        $em->flush();
                }
                else $greske[]="Nova lozinka se ne podudara sa ponovljenom";
            }
            else{
                $greske[]="Pogrešan unos stare lozinke";
            }

        }
        return $this->render("personal/personalpage.html.twig",[
            'user' => $logiraniKorisnik, 'errors' => $greske
        ]);
    }

    public function provjeraStareLozinke($staraLozinka,$unesenaStaraLozinka) {
        if($staraLozinka == $unesenaStaraLozinka)return true;
        return false;
    }
    public function provjeraNovihLozinki($novalozinka,$ponovljenalozinka){
        if($novalozinka == $ponovljenalozinka)return true;
        return false;
    }
   



}
