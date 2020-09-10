<?php

namespace App\Repository;

use App\Entity\AnnonceValidation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnnonceValidation|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnnonceValidation|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnnonceValidation[]    findAll()
 * @method AnnonceValidation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceValidationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnnonceValidation::class);
    }

    // /**
    //  * @return AnnonceValidation[] Returns an array of AnnonceValidation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnnonceValidation
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
