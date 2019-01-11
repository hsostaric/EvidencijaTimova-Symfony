<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 11-Jan-19
 * Time: 13:34
 */

namespace App\Controller;


use App\Repository\KorisnikRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin/users" , name="all_users" );
     */
        public function DohvatiKorisnike(KorisnikRepository $korisnikRepository){
            $users = $korisnikRepository->findAll();
            return $this->render('personal/allUsers.html.twig',['users'=>$users]);

        }

}
