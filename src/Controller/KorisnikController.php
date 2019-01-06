<?php

namespace App\Controller;

use App\Entity\Korisnik;
use App\Forme\RegistrationForm;
use App\Repository\KorisnikRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\MailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use RandomStringGenerator\RandomStringGenerator;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class KorisnikController extends AbstractController
{


    /**
     * @Route("/reg", name="registracija_korisnika")
     */
    public function registracija(\Swift_Mailer $mailer,Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator,KorisnikRepository $korisnikRepository){
        $greske=array();
        $form=$this->createForm(RegistrationForm::class);

       $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $generiraniKod=$this->GenerateRandomString();
            $novi= $form->getData();
            $zauzetost=$korisnikRepository->PronalazakKorisnika($novi["Username"]);
            if(empty($zauzetost)){
                $noviKorisnik= new Korisnik();
                $noviKorisnik->setIme($novi["Name"])
                    ->setPrezime($novi["Surname"])
                    ->setPassword($passwordEncoder->encodePassword($noviKorisnik,$novi["Password"]))
                    ->setLozinkaBackUp($novi["Password"])
                    ->setEmail($novi["Email"])
                    ->setUsername($novi["Username"])
                    ->setAktiviran(false)
                    ->setAktivacijskiKod($generiraniKod);
                $entityManager=$this->getDoctrine()->getManager();
                $entityManager->persist($noviKorisnik);
                $entityManager->flush();
                $message=(new \Swift_Message("Potvrda registracije"))->setFrom('info@moja-stranica.com')->setTo($novi["Email"])->setBody($generiraniKod,'text/plain');
                $mailer->send($message);

                return $this->redirectToRoute('reg_config');
            }
            else $greske[]="Korisnicko ime je zauzeto";

        }


        return $this->render('security/registration.html.twig', [
        'forma'=>$form->createView(),'errors'=>$greske
        ]);
    }
    public function GenerateRandomString():string {
        $generator= new RandomStringGenerator();
        return $generator->generate('lLLDlLddLdLLLdld');
    }

    /**
     * @Route("/confirmReg", name="reg_config")
     */
    public function confirmCode(Request $request,KorisnikRepository $korisnikRepository){
        $korisnik=null;
        $greske=array();
        if($request->isMethod("POST")){

            $korisnik=$korisnikRepository->PronalazakKorisnika($request->request->get('username'));

            if(!empty($korisnik)){
                if($korisnik[0]->getAktivacijskiKod()===$request->request->get('potvrdniKod') && $korisnik[0]->getAktiviran()===false){
                    $repository=$this->getDoctrine()->getManager();
                    $korisnik[0]->setAktiviran(true);
                    $korisnik[0]->setRoles(["Prijavljen_Korisnik"]);
                    $repository->persist($korisnik[0]);
                    $repository->flush();

                    return $this->redirectToRoute('pocetakStranice');
                }
                else $greske[]="Aktivacijski kod se ne podudara sa generiranim/korisnik je već aktiviran";
            }
            else $greske[]="Korisnicko ime nije pronadjeno u bazi.";
        }
        return $this->render('security/potvrdaKoda.html.twig',['errors'=>$greske,
            ]);
    }


}
