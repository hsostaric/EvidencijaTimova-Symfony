<?php

namespace App\Controller;

use App\Repository\KorisnikRepository;
use phpDocumentor\Reflection\Types\This;
use RandomStringGenerator\RandomStringGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{


    /**
     * @Route("/login", name="user_login");
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function Login(AuthenticationUtils $authenticationUtils){
        $zadnjiLogin=$authenticationUtils->getLastUsername();
        $greske=$authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig',[
            'lastLogin'=>$zadnjiLogin,
            'errors'=>$greske,
        ]);
    }

    /**
     * @Route("/logout",name="user_logout");
     */
    public function logout(){
        throw new \Exception('Will be intercepted before getting here');
    }

    /**
     * @Route("/passwordRecovery", name="user_recovery");
     */
    public function RecoveryPassword(\Swift_Mailer $mailer,KorisnikRepository $korisnikRepository,Request $request,UserPasswordEncoderInterface $encoder){
        $greske=array();
        $lozinka=null;
        if ($request->isMethod('POST')){
            $korisnik= $korisnikRepository->PronalazakKorisnika($request->request->get('username'));
            if(!empty($korisnik)){
                $lozinka=$this->GenerateRandomString();
                $korisnik[0]->setPassword($encoder->encodePassword($korisnik[0],$lozinka));
                $korisnik[0]->setLozinkaBackUp($lozinka);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($korisnik[0]);
                $entityManager->flush();
                $message=(new \Swift_Message("Nova lozinka"))->setFrom('info@moja-stranica.com')->setTo($korisnik[0]->getEmail())->setBody($lozinka,'text/plain');
                $mailer->send($message);
                return $this->redirectToRoute("user_login");
            }
            else $greske[]="Korisnicko ime je nepostojeće.";
        }

        return $this->render("security/zaboravljenaLozinka.html.twig",[
            'errors'=>$greske,
        ]);
    }

    public function GenerateRandomString():string {
        $generator= new RandomStringGenerator();
        return $generator->generate('llldlLdl');
    }

}
