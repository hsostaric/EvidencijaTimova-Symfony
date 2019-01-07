<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use App\Repository\TimRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER");
 */
class StudentController extends AbstractController
{
    /**
     * @Route("/studenti", name="student_list")
     */
    public function PrikazStudenata(StudentRepository $repository, Request $request)
    {
        if($request->isMethod('POST')){
            return $this->redirectToRoute('kreiranje_studenta');
        }
        $popisStudenata=$repository->findAll();
        return $this->render('student/studenti.html.twig', [
            'students'=>$popisStudenata,
        ]);
    }
    /**
     * @Route("/novi-student", name="kreiranje_studenta")
     */
    public function DodajStudenta(Request $request){
        $greske=array();
        $ime=$request->request->get('imeStudenta');
        $prezime=$request->request->get('prezimeStudenta');
        $status=$request->request->get('statusStudenta');
        $email=$request->request->get('emailKorisnika');
        $napomena=$request->request->get('napomena');
        if ($request->isMethod('POST') ){
            if(empty($ime) || empty($prezime) || empty($prezime) || empty($status) || empty($email)) $greske[]="Nisu popunjena sva polja";
            else{
                $em=$this->getDoctrine()->getManager();
                $noviStudent = new Student();
                $noviStudent->setIme($ime)->setPrezime($prezime)->setStatus($status)->setEmail($email)->setNapomena($napomena);
                $em->persist($noviStudent);
                $em->flush();

                return $this->redirectToRoute('student_list');
            }
        }

        return $this->render('student\novi.html.twig',[
            'errors'=>$greske,
        ]);
    }

    /**
     * @Route("/studenti/pridruzi-timu/{slug}", name="student_to_team");
     */
    public function PridruziTimStudentu(Request $request,TimRepository $timRepository,StudentRepository $studentRepository,$slug){
        $timovi=$timRepository->findAll();
        $student=$studentRepository->findOneBy(['id'=>$slug]);

        $odabraniTim=null;
        if ($request->isMethod('POST')){
            foreach($timovi as $tim){
                if($tim->getOznakaTima()===$request->request->get('oznakaTima')){
                    $odabraniTim=$tim;
                    break;
                }
            }
            $em=$this->getDoctrine()->getManager();
            $odabraniTim->addStudent($student);
            $em->persist($odabraniTim);
            $em->flush();

            return $this->redirectToRoute('student_list');

        }
        return $this->render('student\pridruziTimu.html.twig',
            [
                'teams'=>$timovi,
            ]);
    }
    /**
     * @Route("/studenti/azuriraj-studenta/{slug}", name="update_student");
     */
    public function AzurirajStudenta(Request $request,StudentRepository $studentRepository, TimRepository $timRepository,$slug){
        $studentZaUpdate=$studentRepository->findOneBy(['id'=>$slug]);
        $timovi=$timRepository->findAll();
        if($request->isMethod('POST')){
                $em=$this->getDoctrine()->getManager();
                $studentZaUpdate->setIme($request->request->get('imeStudenta'))->setPrezime($request->request->get("prezimeStudenta"))->setEmail($request->request->get("emailKorisnika"))
                    ->setStatus($request->request->get('statusStudenta'))->setNapomena($request->request->get('napomena'));

                $em->persist($studentZaUpdate);
                $em->flush();

                return $this->redirectToRoute('student_list');
            }

        return $this->render('student/updateStudent.html.twig',
            ['student'=>$studentZaUpdate,
              'teams'=>$timovi,
                ]);
    }
}
