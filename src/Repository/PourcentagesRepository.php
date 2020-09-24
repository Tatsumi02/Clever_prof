<?php

namespace App\Repository;

use App\Entity\Pourcentages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pourcentages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pourcentages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pourcentages[]    findAll()
 * @method Pourcentages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PourcentagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pourcentages::class);
    }

    // /**
    //  * @return Pourcentages[] Returns an array of Pourcentages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    //upPrc
    public function  upPrc($prc): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE pourcentages a SET
         a.pourcentage = :typ
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['typ'=>$prc]);
 
    //
 }


    /*
    public function findOneBySomeField($value): ?Pourcentages
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
