<?php

namespace App\Repository;

use App\Entity\CvAnonceur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CvAnonceur|null find($id, $lockMode = null, $lockVersion = null)
 * @method CvAnonceur|null findOneBy(array $criteria, array $orderBy = null)
 * @method CvAnonceur[]    findAll()
 * @method CvAnonceur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CvAnonceurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CvAnonceur::class);
    }

    // /**
    //  * @return CvAnonceur[] Returns an array of CvAnonceur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CvAnonceur
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
