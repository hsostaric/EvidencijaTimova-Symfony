<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 11-Jan-19
 * Time: 13:34
 */

namespace App\Controller;


use App\Entity\Korisnik;
use App\Funkcije\Funkcije;
use App\Repository\KorisnikRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
            $uvjet=false;
            $nonAdmini=array();
            foreach ($users as $user){

                foreach ($user->getRoles() as $role){
                    $uvjet=false;
                    if($role == "ROLE_ADMIN"){
                       $uvjet=true;
                       break;
                    }
                    if ($uvjet===false)$nonAdmini[]=$user;
                }
            }
            return $this->render('personal/allUsers.html.twig',['users'=>$users, 'nonAdmini'=>$nonAdmini]);

        }

        /**
         * @Route("/admin/users/delete/{id}", name="delete_user");
         */

        public function deleteUser(KorisnikRepository $korisnikRepository,$id,EntityManagerInterface $entityManager){
            $korisnik=$korisnikRepository->findOneBy([
                'id'=>$id
            ]);

            $em= new Funkcije();
            $em->deleteEntity($entityManager,$korisnik);
            return $this->redirectToRoute('all_users');
        }

        /**
         * @Route("/admin/user/unblock/{id}", name="update_user");
         */
        public function unblockUser(KorisnikRepository $korisnikRepository,$id,EntityManagerInterface $entityManager){
            $korisnik = $korisnikRepository->findOneBy(['id'=>$id]);
            $em= new Funkcije();
            $em->pohraniPromjene($entityManager,$korisnik->setBlokiran(false));
            return $this->redirectToRoute('all_users');
        }

        public
}
