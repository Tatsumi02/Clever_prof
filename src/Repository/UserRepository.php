<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function  updateRole($id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.roles =\'["ROLE_USER","ROLE_PROF"]\'
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id]);

        //
    
    }

    public function  updatePdp($newFilename,$id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.pdp = :name
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id,'name'=>$newFilename]);
    
    }

    public function  updateNom($new_nom,$id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.nom = :name
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id,'name'=>$new_nom]);

        
    
    }

    public function  updatePrenom($new_prenom,$id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.prenom = :name
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id,'name'=>$new_prenom]);

    }

    public function  updatePhone($new_phone,$id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.phone_portable = :name
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id,'name'=>$new_phone]);

    }

    public function  updateAdresse($new_adresse,$id): void
    {
        $conn = $this -> getEntityManager()->getConnection();
        $sql = '
        UPDATE user a SET
            a.adresse = :name
        
        WHERE a.id =:id
        ';
        $stmt = $conn ->prepare($sql);
        $stmt->execute(['id'=>$id,'name'=>$new_adresse]);

    }





    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
