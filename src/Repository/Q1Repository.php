<?php

namespace App\Repository;

use App\Entity\Q1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Q1|null find($id, $lockMode = null, $lockVersion = null)
 * @method Q1|null findOneBy(array $criteria, array $orderBy = null)
 * @method Q1[]    findAll()
 * @method Q1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Q1Repository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Q1::class);
    }

    // /**
    //  * @return Q1[] Returns an array of Q1 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Q1
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
