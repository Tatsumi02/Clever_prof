<?php

namespace App\Repository;

use App\Entity\Matiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matiere[]    findAll()
 * @method Matiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }

    // /**
    //  * @return Matiere[] Returns an array of Matiere objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ; 
    }
    */
    public function  upToDate($id,$cours,$branche): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE matiere a SET
            a.cours =:cours,
            a.branche =:branche
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['cours'=>$cours,'branche'=>$branche,'id'=>$id]);

        //
    
    }
    public function deler($id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        DELETEup FROM matiere
        WHERE id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id]);

        //
    
    }


    /*
    public function findOneBySomeField($value): ?Matiere
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
