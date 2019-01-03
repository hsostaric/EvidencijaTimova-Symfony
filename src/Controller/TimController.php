<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TimController extends AbstractController
{
    /**
     * @Route("/tim", name="tim")
     */
    public function index()
    {
        return $this->render('tim/index.html.twig', [
            'controller_name' => 'TimController',
        ]);
    }
    /**
     * @Route("/", name="pocetakStranice")
     */
    public function pocetna(){
        return $this->render('tim/pocetna.html.twig');
    }
}
