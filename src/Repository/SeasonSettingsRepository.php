<?php

namespace App\Repository;

use App\Entity\SeasonSettings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SeasonSettings|null find($id, $lockMode = null, $lockVersion = null)
 * @method SeasonSettings|null findOneBy(array $criteria, array $orderBy = null)
 * @method SeasonSettings[]    findAll()
 * @method SeasonSettings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonSettingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SeasonSettings::class);
    }

    // /**
    //  * @return SeasonSettings[] Returns an array of SeasonSettings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SeasonSettings
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
