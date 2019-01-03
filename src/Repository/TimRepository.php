<?php

namespace App\Repository;

use App\Entity\Tim;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tim|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tim|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tim[]    findAll()
 * @method Tim[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tim::class);
    }

    // /**
    //  * @return Tim[] Returns an array of Tim objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tim
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
