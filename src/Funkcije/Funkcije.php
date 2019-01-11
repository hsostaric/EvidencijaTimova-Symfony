<?php
/**
 * Created by PhpStorm.
 * User: Hrvoje
 * Date: 11-Jan-19
 * Time: 12:52
 */

namespace App\Funkcije;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class Funkcije
{

    public static function pohraniPromjene(EntityManagerInterface $entityManager,object $object){
       $entityManager->persist($object);
       $entityManager->flush();
    }

    public  static function deleteEntity(EntityManagerInterface $entityManager, object $object){
        $entityManager->remove($object);
        $entityManager->flush();

    }
}
