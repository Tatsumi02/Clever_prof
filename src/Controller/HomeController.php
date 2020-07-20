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

class HomeController extends AbstractController
{
  //constructeur pour encoder les mots de pass dans la bdd
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
      $this->passwordEncoder = $passwordEncoder;
  }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/enregistrer-vous", name="enregistrement")
     */

    public function enregistrement(){
      return $this->render('home/enregistrement.html.twig');
    }

    /**
     * @Route("/inscriptions ", name="inscription")
     */

    public function inscription(Request $request){
      
      //nous alons dabord faire une lecture dans la base de donnee pour voir si l'email que l'utilisateur essaye d'entrer n'existe pas deja
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
       $user -> setRoles(['ROLE_USER']);
       $user -> setActif('true');
       $user -> setDateInscrit(new \DateTime());
       $em -> persist($user);
       $em -> flush();

     }else{
       //si l'email existe deja on lui renvoir vers la page derreur
       return new Response('deso votre email est deja utiliser');
     }
        

        return $this->redirectToRoute('acount');
      }

  
    /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/compte", name="acount")
     */
    public function acount(){
        

        return $this->render('home/index_login.html.twig',[

          'user' => $this->getUser()->getPrenom(),
        ]);
    }



}
