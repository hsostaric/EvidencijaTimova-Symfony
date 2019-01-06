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
    /**
     * @Route("/allUsers", name="all_users");
     */
    public function show(KorisnikRepository $korisnikRepository){
        $korisnici=$korisnikRepository->findAll();
        return $this->render('personal/allUsers.html.twig',[
            'users'=>$korisnici,
        ]);
    }
}
