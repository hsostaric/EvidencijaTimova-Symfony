<?php

namespace App\Controller;

use App\Entity\Korisnik;
use App\Forme\RegistrationForm;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function registracija(\Swift_Mailer $mailer,Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator){

        $form=$this->createForm(RegistrationForm::class);

       $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $generiraniKod=$this->GenerateRandomString();
            $message=(new \Swift_Message("Potvrda koda"))->setFrom("hrvoje.sostaric@hotmail.com")->setTo("SweetThunder@mailinator.com")->setBody($generiraniKod,'text/plain');
            $mailer->send($message);

            $novi= $form->getData();
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
