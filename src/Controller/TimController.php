<?php

namespace App\Controller;

use App\Entity\Tim;
use App\Repository\TimRepository;
use Doctrine\DBAL\Types\TimeImmutableType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TimController extends AbstractController
{
    /**
 * @Route("/", name="pocetakStranice")
 */
    public function pocetna(){
        return $this->render('tim/pocetna.html.twig');
    }
    /**
     * @Route("/timovi", name="popis_timova");
     * @IsGranted("ROLE_USER");
     */
    public function prikazTimova(TimRepository $timRepository,Request $request)
    {
        $timovi=$timRepository->findAll();
        if($request->isMethod('POST')){
            return $this->redirectToRoute('kreiranje_tima');
        }

        return $this->render('tim\timovi.html.twig',['teams'=>$timovi]);
    }

    /**
     * @Route("/noviTim",name="kreiranje_tima");
     * @IsGranted("ROLE_USER")
     */
    public function kreirajTim(Request $request,TimRepository $timRepository){
        $greske=array();
        $tim=null;
        if ($request->isMethod('POST')){
            $tim=$timRepository->findOneBy(['oznakaTima'=>$request->request->get('oznakaTima')]);
            if(!empty($tim))$greske[]="Oznaka tima je već postojeća.";
            else{
                $timNovi= new Tim();
                $timNovi->setOznakaTima($request->request->get('oznakaTima'));
                 $timNovi->setNazivProjekta($request->request->get('nazivProjekta'));
                 $timNovi->setOpisProjekta($request->request->get('opisProjekta'));
                 $timNovi->setNapomena($request->request->get('napomena'));
                $em=$this->getDoctrine()->getManager();
                $em->persist($timNovi);
                $em->flush();
                return $this->redirectToRoute('popis_timova');

            }
        }
        return $this->render('tim\novi.html.twig',['errors'=>$greske]);
    }
    /**
     * @Route("timovi/clanovi/{id}", name="students_in_team");
     * @IsGranted("ROLE_USER")
     */
    public function PrikazClanova($id,TimRepository $timRepository){
        $tim=$timRepository->findOneBy(['id'=>$id]);

        return $this->render('tim\clanovi.html.twig',
            ['team'=>$tim]);
    }

    /**
     * @Route("timovi/azuriraj/{id}", name="update_team");
     * @IsGranted("ROLE_USER")
     */
    public function UpdateTeam($id,TimRepository $timRepository,Request $request){
        $greske=array();
        $tim= $timRepository->findOneBy(['id'=>$id]);
        if ($request->isMethod('POST')){
            $pom=$timRepository->findOneBy(['oznakaTima'=>$request->request->get('oznakaTima')]);
            if(!empty($pom))$greske[]="Novo ime tima već postoji";
            else{
                $tim->setOznakaTima($request->request->get('oznakaTima'))->setNazivProjekta($request->request->get('nazivProjekta'))->setOpisProjekta($request->request->get('opisProjekta'))->setNapomena($request->request->get('napomena'));

                $em=$this->getDoctrine()->getManager();
                $em->persist($tim);
                $em->flush();
                return $this->redirectToRoute('popis_timova');
            }
        }
        return $this->render('tim\updateTeam.html.twig',['team'=>$tim,
            'errors'=>$greske]);
    }
    /**
     * @Route("timovi/delete/{id}", name="delete_team");
     * @IsGranted("ROLE_USER")
     */
    public function deleteTeam($id,TimRepository $timRepository){

        $timZaBrisanje=$timRepository->findOneBy(['id'=>$id]);
        if(empty(count($timZaBrisanje->getStudents()))){
            $em=$this->getDoctrine()->getManager();
            $em->remove($timZaBrisanje);
            $em->flush();
            return $this->redirectToRoute('popis_timova');
        }
        return $this->redirectToRoute('popis_timova');
    }

}
