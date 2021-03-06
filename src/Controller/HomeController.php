<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Anonce;
use App\Entity\Notion;
use App\Entity\Commande;
use App\Repository\UserRepository;
use App\Repository\NotionRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    
      //on va verifier si l'utillisateur a une session en cours.
      if($this->getUser()!=null){
        //si il en a, on verifie le role de la session en cours
        if($this->getUser()->getRoles()[0] == 'ROLE_PROF'){
          //si le role est 'professeurs'
          return $this->redirectToRoute('professeurs'); //on le redirige vers la sessions des professeurs
        }

        if($this->getUser()->getRoles()[0] == 'ROLE_ETUD'){
          //si le role est etudiant
          return $this->redirectToRoute('etudiants'); //on redirige vers la session des etudiants
        }

       //si il n'a pas de session en cours,on le renvoir en accueil
      }
      // else{

        //on recupere la liste des annonces disponibles
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $repository2 = $this -> getDoctrine() -> getRepository(User::class);
        $anonce = $repository -> annon();

        $profil = $repository2 -> findAll();
        $r = '';

        /*foreach($profs as $prof){
          if($prof->getRoles() =='ROLE_PROF'){
             $r = $prof->getRoles();

          }
           $profil = $repository2 -> findBy(['roles'=>$r]);

        }*/

        return $this->render('home/index.html.twig', [
            'anonces' => $anonce,
            'profil' => $profil,
        ]);

      // }
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
       $user -> setPdp('default_pdp.JPG');
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
     * @Route("/Accuiel.html", name="acount")
     */
    public function acount(){
    //se controlleur permet de gerer laccueil d'un compte conecter
        //on recupere la liste des annonces disponibles
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $anonce = $repository -> annon();
        $profil = $repository -> profi();
         
        return $this->render('home/index_login.html.twig',[
          'user' => $this->getUser()->getPrenom(),
          'anonces' => $anonce,
          'profil' => $profil,
        ]);
    }

     /**
     * @Route("/search", name="find_anonce")
     */
    public function search(Request $request){
        
        $cours = $request->get('search');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $alls = $repository -> findBy(['actif'=>'true']);
        $matiere = '';
        $anonce = false;
        $certifier = false;

        foreach($alls as $all){
          $chaine = explode('-',$all->getMatiere());
          $cour = $chaine[0];
        //verifions si l'annonce est certifier ou non
        if($all->getCertifier() == 'true'){
          $certifier = true;
        }

        //recuperons les cours qui corespondent a la recherche entrer par l'utilisateur
          if($cour == $cours){
             $matiere = $all->getCours();
             $anonce = $repository -> findBy(['cours'=>$matiere,'actif'=>'true']);

               $nombre_resultat = 0;
              //comptons le  nombre de resultat optenue sur chaque recherches trouver
              foreach($anonce as $anon){
                 $nombre_resultat++;
               }
          }
        }

        

        if($anonce == false){
          return $this->redirectToRoute('home');
        }
      
        return $this->render('home/search.html.twig',[
          'anonces' => $anonce,
          'cours' => $cours,
          'certifier' => $certifier,
          'nombre_resultat' => $nombre_resultat,
        ]);
    }

    
   /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
    * @Route("/annonce/{id}/view", name="view_anonce")
    */
    public function view($id){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $info = $repository -> findBy(['id'=>$id]);
      $certifier = false;
      foreach($info as $cert){
        if($cert->getCertifier() == 'true'){
          $certifier = true;
          
        }
      }

      return $this->render('home/view_anonce.html.twig',[
        'infos' => $info,
        'certifier' => $certifier,
      ]);
    }

    /**
     * @Route("/data_prof", name="data_prof")
     */
    public function dataProf(Request $request){
      $id = $request->get('id');
      if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
        $jsonData = array();
        $idx=0;
        $repository = $this -> getDoctrine() -> getRepository(User::class);
            $annonce = $repository -> findBy(['id'=>$id]);
            
            foreach($annonce as $anon){

              $temp = array(
                'nom' => $anon->getNom(),
                'prenom' => $anon->getPrenom(),
                'adresse' => $anon->getAdresse(),
                'phone_portable' => $anon->getPhonePortable(),
                'genre' => $anon->getGenre(),
                'ville' => $anon->getVille(),
                'pdp' => $anon->getPdp(),
              );

              $jsonData[$idx++] = $temp;
          }
          return new JsonResponse($jsonData);
           
      }else{ 
        return new Response('ce n\'est pas une requette ajax');
      }

    }

    
     /**
     * @Route("/ajax_notion", name="ajax_notion_")
     */
    public function ajaxNotion(Request $request){
      $id = $request->get('id');
      if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
        $jsonData = array();
        $idx=0;
        $repository = $this -> getDoctrine() -> getRepository(Notion::class);
            $notion = $repository -> findBy(['annonceur_id'=>$id]);
            
            foreach($notion as $anon){

              $temp = array(
                'notion' => nl2br($anon->getNotion()),
                'date_notion' => $anon->getDateNotion(),
              );

              $jsonData[$idx++] = $temp;
          }
          return new JsonResponse($jsonData);
           
      }else{ 
        return new Response('ce n\'est pas une requette ajax');
      }

    }


    /**
     * * @Route("/{id}/annonce", name="choix")
     *
     */
    public function choix(Request $request,$id){
      //faisons une lecture dans la bd pour savoir si l'annonce demander a des notions ou non
      $repository = $this -> getDoctrine() -> getRepository(Notion::class);
      $notion = $repository -> findBy(['annonce_id'=>$id]);
      $isNotion = false;
      foreach($notion as $notio){
        $isNotion = true;
      }


      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $info = $repository -> findBy(['id'=>$id]);
      $certifier = false;
      $ida = 3 ;
      foreach($info as $cert){
        if($cert->getCertifier() == 'true'){
          $certifier = true;
         
        }
        $ida = $cert->getAnonceurId();
      }

      $repository2 = $this -> getDoctrine() -> getRepository(User::class);
      $pdpp = $repository2 -> findOneBy(['id'=>$ida]);

      return $this->render('home/choix_annonce.html.twig',[
        'infos' => $info,
        'certifier' => $certifier,
        'pdp' => $pdpp->getPdp(),
        'notion' => $isNotion,
      ]);
    }

    /**
     * * @Route("/{id}/prendre-contact", name="ma_formation")
     *
     */
    public function ma_formation(Request $request,$id){
      
      //prenon les infos sur l'annonce en cours
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonce = $repository -> findOneBy(['id'=>$id]);

      
      return $this->render('home/ma_formation.html.twig',[
       'cours' => $anonce->getCours(),
       'id_a' => $anonce->getAnonceurId(),
       'annonce_id' => $anonce->getId(),
      ]);
    }

    /**
     * * @Route("/{annonce_id}/save-commande", name="commande")
     *
     */
    public function commande(Request $request,$annonce_id){
      //essyons d'abord de recuperer l'id de l'utilisateur cours 

      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonces = $repository -> findBy(['id'=>$annonce_id]);
      $annoncur_id = 0;
      foreach($anonces as $anonce){
        $annoncur_id = $anonce->getAnonceurId();
      }


      $nom = $request->get('nom');
      $prenom = $request->get('prenom');
      $email = $request->get('email');
      $phone = $request->get('phone');
      $message = $request->get('message');

      $em = $this -> getDoctrine() -> getManager();
  
      $commande = new Commande();
      $commande->setNom($nom);
      $commande->setPrenom($prenom);
      $commande->setPhone($phone);
      $commande->setEmail($email);
      $commande->setAnnonceId($annonce_id);
      $commande->setDateCommande(new \DateTime());
      $commande->setStatus('true');
      $commande->setMessage($message);
      $commande->setAnnonceurId($annoncur_id);
      $commande->setStatut(1);

      $em->persist($commande);
      $em->flush();

      return $this->redirectToRoute('commande_faait',['annonce_id'=>$annonce_id]);
    }

     /**
     * * @Route("/{annonce_id}/commande-fait", name="commande_faait")
     *
     */
      public function commandeFait($annonce_id){
        
        return $this->render('home/commande_fait.html.twig');
      }


}
