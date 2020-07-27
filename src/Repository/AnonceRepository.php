<?php

namespace App\Repository;

use App\Entity\Anonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Anonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Anonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Anonce[]    findAll()
 * @method Anonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anonce::class);
    }

    // /**
    //  * @return Anonce[] Returns an array of Anonce objects
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
    public function updateTypeCours($typeC,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.type_cours =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$typeC, $id, $b]);

        // 
    
    }

    public function  updateTypeTitre($typeC,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.titre =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$typeC, $id, $b]);

        // updateParcours($request->get('parcours'),$this->getUser()->getId(),$b)
    
    }

    public function  updateParcours($parcours,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.parcours =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$parcours, $id, $b]);

        // 
    
    }

    public function  updateMethodologie($parcours,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.methodologie =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$parcours, $id, $b]);

        // 
    
    }

    public function  updateLieuCours($parcours,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.lieu_cours =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$parcours, $id, $b]);

        //updateTarifHeure
    
    }

    public function  updateTarifHeure($parcours,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.tarif_heure =?
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$parcours, $id, $b]);

        //updatePdp($newFilename,$id,$b)
    
    }

    public function  updatePdp($newFilename,$id,$b): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.photo_profil =?,
            a.actif = "true"
        
        WHERE a.anonceur_id =? AND a.matiere =?
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute([$newFilename, $id, $b]);

        //
    
    }

    


    /*
    public function findOneBySomeField($value): ?Anonce
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
