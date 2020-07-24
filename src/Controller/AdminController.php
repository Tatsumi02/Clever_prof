<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Matiere;
use App\Repository\MatiereRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;


class AdminController extends AbstractController
{
    //constructeur pour encoder les mots de pass dans la bdd
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
      $this->passwordEncoder = $passwordEncoder;
  }

    /**
     * @Route("/admin-init", name="init")
     */
    public function index()
    {

        $em = $this -> getDoctrine() -> getManager();
        $user = new User();

       $user -> setNom('Admin');
       $user -> setPrenom('Cool');
       $user -> setEmail('admin@admin');
       
       $user->setPassword($this->passwordEncoder->encodePassword(
        $user,
        '1234'
       ));

       $user -> setPhoneFixe('indef');
       $user -> setPhonePortable('indef');
       $user -> setVille('indef');
       $user -> setAdresse('indef');
       $user -> setPays('Cameroun');
       $user -> setGenre('indef');
       $user -> setRoles(['ROLE_ADMIN']);
       $user -> setActif('true');
       $user -> setDateInscrit(new \DateTime());
       $em -> persist($user);
       $em -> flush();



      return $this -> redirectToRoute('admin');
    }

     /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/admin", name="admin")
     */
    public function homeAdmin(){
       
        return $this -> render('admin/index.html.twig');
    }


    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/ajout-matieres", name="ajout_matiere")
     */
    public function matiere(Request $request){
        $erreur = $request->get('erreur');
        
        return $this -> render('admin/matiere.html.twig',[
            'erreur' => $erreur,
        ]);
    }
     
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/traitement", name="add_cours")
     */
    public function traitement(Request $request){
        //nous devons d'abor verifier que l'admin n'essaie pas d'entrer deux fois une meme matiere
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matiereExist = $repository -> findOneBy(['cours'=>$request->request->get('cours')]);
        $isExixt = false;
        if ($matiereExist) {
             if ($matiereExist->getCours() == $request->request->get('cours')) {
                 $isExixt = true;
             }
        }
      $erreur = ''; // cette variable contient le message d'erreur.. si il en a

    if($isExixt == false){

       $cours = $request->request->get('cours');
       $specialite = $request->request->get('specialite');

       $matiere = new Matiere();
       $matiere -> setCours($cours);
       $matiere-> setBranche($specialite);
       $matiere -> setDateAjout(new \DateTime());
       $em = $this -> getDoctrine() -> getManager();
       $em -> persist($matiere);
       $em -> flush();

       return $this->redirectToRoute('admin');

      }else {
          $erreur = 'Desoler le cours sur "'. $request->request->get('cours') .'" Existe deja. vous povez juste le modifier';
          return $this -> redirectToRoute('ajout_matiere',['erreur'=>$erreur]);
      }
      
       return new Response('hmm c\'est bizarre');
    }
       


}
