<?php

namespace App\Repository;

use App\Entity\Notion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notion[]    findAll()
 * @method Notion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notion::class);
    }

    // /**
    //  * @return Notion[] Returns an array of Notion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notion
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
