<?php

namespace App\Controller;

use App\Entity\Korisnik;
use App\Forme\RegistrationForm;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use RandomStringGenerator\RandomStringGenerator;


class KorisnikController extends AbstractController
{


    /**
     * @Route("/reg", name="registracija_korisnika")
     */
    public function registracija(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator){

        $form=$this->createForm(RegistrationForm::class);

       $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

           /* for ($i=0;$i<3;$i++){
                dump($this->GenerateRandomString());
            }die;
            dump($this->GenerateRandomString());
            $novi= $form->getData();
            $noviKorisnik= new Korisnik();
            $noviKorisnik->setIme($novi["Name"]);
            $noviKorisnik->setPrezime($novi["Surname"]);
            $noviKorisnik->setPassword($passwordEncoder->encodePassword($noviKorisnik,$novi["Password"]));
            $noviKorisnik->setEmail($novi["Email"]);
            $noviKorisnik->setUsername($novi["Username"]);
            $noviKorisnik->setAktiviran(false);
            $noviKorisnik->setAktivacijskiKod($this->GenerateRandomString());
            $*/
        }

        return $this->render('security/registration.html.twig', [
        'forma'=>$form->createView(),

        ]);
    }
    public function GenerateRandomString():string {
        $generator= new RandomStringGenerator();
        return $generator->generate('lLLDlLddLdLLLdld');
    }
}
