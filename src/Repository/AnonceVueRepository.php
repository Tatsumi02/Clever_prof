<?php

namespace App\Repository;

use App\Entity\AnonceVue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnonceVue|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnonceVue|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnonceVue[]    findAll()
 * @method AnonceVue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnonceVueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnonceVue::class);
    }

    // /**
    //  * @return AnonceVue[] Returns an array of AnonceVue objects
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
    public function findOneBySomeField($value): ?AnonceVue
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
