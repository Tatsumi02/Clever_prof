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
    
    public function  annon(): array
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = 'SELECT * FROM anonce a ORDER BY id DESC LIMIT 0,6 ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    
    }

    public function  profi(): array
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = 'SELECT * FROM anonce a  LIMIT 0,15 ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    
    }

    public function  search($matiere): array
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = 'SELECT * FROM anonce m ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    
    }

    public function  update2($id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE anonce a SET
            a.certifier = "true"
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id]);

        //update_type_cours($id,$typeCours)
    
    }

   
//------------------------------------ UPDATE DES CHAMPS ANNONCES ------------------------------------------ 

public function  update_cours($id,$matiere): void
{
    $conn = $this -> getEntityManager()->getConnection();
    $sql = '
    UPDATE anonce a SET
        a.matiere = :mat
    
    WHERE a.id =:id
    ';
    $stmt = $conn ->prepare($sql);
    $stmt->execute(['id'=>$id,'mat'=>$matiere]);

    //

 }

 public function  update_type_cours($id,$typeCours): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.type_cours = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$typeCours]);
 
    //update_titre($id,$titre)
 }
  
 public function  update_titre($id,$titre): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.titre = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$titre]);
 
    //update_parcours($id,$parcours)
 }

 public function  update_parcours($id,$parcours): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.parcours = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$parcours]);
 
    //update_methodologie($id,$methodologie)
 }

 public function  update_methodologie($id,$methodologie): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.methodologie = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$methodologie]);
 
    //update_lieux_cours($id,$lieux_cours)
 }

 public function  update_lieux_cours($id,$lieux_cours): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.lieu_cours = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$lieux_cours]);
 
    //update_tarif_heure($id,$tarif_heure);
 }

 public function  update_tarif_heure($id,$tarif_heure): void
 {
     $conn = $this -> getEntityManager()->getConnection();
     $sql = '
     UPDATE anonce a SET
         a.tarif_heure = :typ
     
     WHERE a.id =:id
     ';
     $stmt = $conn ->prepare($sql);
     $stmt->execute(['id'=>$id,'typ'=>$tarif_heure]);
 
    //
 }




 //----------------------------------------FIN UPDATE---------------------------------------

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
