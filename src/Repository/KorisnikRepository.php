<?php

namespace App\Repository;

use App\Entity\Korisnik;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Korisnik|null find($id, $lockMode = null, $lockVersion = null)
 * @method Korisnik|null findOneBy(array $criteria, array $orderBy = null)
 * @method Korisnik[]    findAll()
 * @method Korisnik[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KorisnikRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Korisnik::class);
    }

    // /**
    //  * @return Korisnik[] Returns an array of Korisnik objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('k.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Korisnik
    {
        return $this->createQueryBuilder('k')
            ->andWhere('k.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
