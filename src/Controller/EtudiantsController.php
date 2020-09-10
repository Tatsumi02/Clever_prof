<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Repository\UserRepository;        
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;




 /**
  * @Route("/etudiants")
  */
class EtudiantsController extends AbstractController
{
     //constructeur pour encoder les mots de pass dans la bdd
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
      $this->passwordEncoder = $passwordEncoder;
  }


    /**
     * @Route("/", name="etudiants")
     */
    public function index()
    {
        return $this->render('etudiants/index.html.twig', [
            'controller_name' => 'EtudiantsController',
        ]);
    }

    /**
     * @Route("/inscription", name="netudiant")
     */
    public function nEtudiant(){
        return $this->render('etudiants/inscription.html.twig');
    }


    /**
     * @Route("/new-etudiant-traitement", name="inscription_etudiant_traitement")
     */
    public function I_E_Traitement(Request $request){
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $emailExist = false;
              $userData = $repository -> findOneBy(['email'=>$request->request->get('email')]);
            if($userData){
              if ($request->request->get('email') == $userData->getEmail()) {
                $emailExist = true;
              }
            }
  
       if($emailExist == false){ //si l'email n'est pas encore utiliser
         //commencon par reccuperer les informations d'inscription pris dans le formulaire de d'inscription
         $nom = $request->request->get('nom');
         $prenom = $request->request->get('prenom');
         $email = $request->request->get('email');
         $pass1 = $request->request->get('pass1');
         $pass2 = $request->request->get('pass2');
         $tel_fixe = $request->request->get('tel_fixe');
         $tel_portable = $request->request->get('tel_portable');
         $ville = $request->request->get('ville');
         $adresse = $request->request->get('adresse');
         $genre = $request->request->get('genre');
  
         $em = $this -> getDoctrine() -> getManager();
         $user = new User();
  
         $user -> setNom($nom);
         $user -> setPrenom($prenom);
         $user -> setEmail($email);
         
         $user->setPassword($this->passwordEncoder->encodePassword(
          $user,
          $pass2
         ));
  
         $user -> setPhoneFixe($tel_fixe);
         $user -> setPhonePortable($tel_portable);
         $user -> setVille($ville);
         $user -> setAdresse($adresse);
         $user -> setPays('Cameroun');
         $user -> setGenre($genre);
         $user -> setRoles(['ROLE_ETUD']);
         $user -> setActif('true');
         $user -> setPdp('default_pdp.JPG');
         $user -> setDateInscrit(new \DateTime());
         $em -> persist($user);
         $em -> flush();
  
       }else{
         //si l'email existe deja on lui renvoir vers la page derreur
         return new Response('deso votre email est deja utiliser');
       }
          
  
        return $this->redirectToRoute('student_home');
          
     }

    /**
    *  Require ROLE_ETUD for only this controller method.
    * 
    * @IsGranted("ROLE_ETUD")
    * @Route("/home", name="student_home")
    */
    public function home(){
      
        return $this->render('etudiants/home.html.twig',[
            '' => ''
        ]);
    }





}
