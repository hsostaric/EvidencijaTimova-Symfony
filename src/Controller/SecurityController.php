<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

}
