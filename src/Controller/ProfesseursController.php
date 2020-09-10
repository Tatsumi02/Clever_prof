<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guad\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Notion;
use App\Entity\Anonce;
use App\Entity\Commande;
use App\Repository\UserRepository;
use App\Repository\NotionRepository;
use App\Repository\CommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;



/**
 * @Route("/professeurs")
 */
class ProfesseursController extends AbstractController
{
    //constructeur pour encoder les mots de pass dans la bdd
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
      $this->passwordEncoder = $passwordEncoder;
  }

    /**
     * @Route("/", name="professeurs")
     */
    public function index()
    {
      if($this->getUser()!=null){
        if($this->getUser()->getRoles()[0] == 'ROLE_PROF'){
          //si le role est 'professeurs'
          return $this->redirectToRoute('dashboad'); //on le redirige vers la sessions des professeurs
        }
      }else{
        return $this->render('professeurs/index.html.twig', [
            'controller_name' => 'ProfesseursController',
        ]);
      }

    } 

    
    /**
     * @Route("/inscription", name="inscription_prof")
     */
    public function inscription(){


        return $this->render('professeurs/inscription.html.twig', [
        '',''
        ]);
    }

    /**
     * @Route("/traitement-inscription", name="traitement-inscription")
     */
    public function traitementInscription(Request $request){
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
         $user -> setRoles(['ROLE_PROF']);
         $user -> setActif('true');
         $user -> setPdp('default_pdp.JPG');
         $user -> setDateInscrit(new \DateTime());
         $em -> persist($user);
         $em -> flush();
  
       }else{
         //si l'email existe deja on lui renvoir vers la page derreur
         return new Response('deso votre email est deja utiliser');
       }
          
        /*  return $this->guardHandler->authenticateUserAndHandleSuccess(
           $user,
           $request,
           $this->authenticator,
           'main'
         ); */
          return $this->redirectToRoute('dashboad');
    }

    //creons le controller pour l'espace de admin. ou nous allons rediriger un proffesseur apres son inscription
    /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/tableau-de-bord", name="dashboad")
     */
    public function dashboad(){
       
      $compteur = 0;
      //nous allons dabord faire une lecture dans tout les annonces pour reccuperer les annonces de l'utilisateur en cours
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonces = $repository -> findBy(['anonceur_id'=>$this->getUser()->getId()]);
      //une fois qu'on a tout les anonces de l'utilisateur en cours, on va boucler dessus
      foreach($anonces as $anonce){
      // on va faire une lectur dans la table commande pour recuperer exactement les commandes effectuer sur les anonces de l'utilisateur en cours
        $repository2 = $this -> getDoctrine() -> getRepository(Commande::class);
        $commandes = $repository2 -> findBy(['annonce_id'=>$anonce->getId(),'status'=>'true','statut'=>1]);
        //maintenant on va boucler sur la deuxieme requette pour compter le nom commande fait pour l'utilisateur en cours
        foreach($commandes as $commande){
          //on incremente notre compteur pour chaque resultats trouver
          $compteur++;
        }  
      }


        return $this->render('professeurs/accueil.html.twig', [
            'commande' => $compteur,
        ]);
    }

    
    /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/modification-annonce", name="modification")
     */
    public function modification(){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $annonces = $repository -> findBy(['anonceur_id'=> $this->getUser()->getId()]);

      return $this->render('professeurs/modification_annonce.html.twig', [
          'annonces' => $annonces,
      ]);
  }

  
   /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/certifier-annonce", name="certifier_annonce")
     */
    public function certifier_anonce(){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $annonces = $repository -> findBy(['anonceur_id'=> $this->getUser()->getId(),'certifier'=>'false']);

      return $this->render('professeurs/certifier_annonce.html.twig', [
          'annonces' => $annonces,
      ]);
  }

  /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/annonce-certifie", name="annonce_certifie")
     */
    public function anonce_certifier(){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $annonces = $repository -> findBy(['anonceur_id'=> $this->getUser()->getId(),'certifier'=>'true']);

      return $this->render('professeurs/annonce_certifie.html.twig', [
          'annonces' => $annonces,
      ]);
  }
  
  /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/annonce-suppression", name="annonce_suppression")
     */
    public function anonce_suppression(){
      
      return $this->render('professeurs/annonce_suppression.html.twig', [
      ]);
  }

  /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/nouveaux-etudiants", name="nouveaux_etudiant")
     */
    public function newStudent(Request $request){
      $compteur = 0;
      $anonce_id = 0;
    
      //nous allons dabord faire une lecture dans tout les annonces pour reccuperer les annonces de l'utilisateur en cours
      $repository = $this -> getDoctrine() -> getRepository(Commande::class);
      $anonces = $repository -> findBy(['annonceur_id'=>$this->getUser()->getId(),'status'=>'true','statut'=>1]);
     
      return $this->render('professeurs/nouveaux_etudiant.html.twig',[
        'commandes'=>$anonces,
      ]);
          
  }

  /**
     * * Require ROLE_PROF,ROLE_ADMIN for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}/annonce-commande", name="commande_view")
     */
    public function commande_view($id){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonces = $repository -> findBy(['id'=>$id]);
     


      return $this->render('professeurs/commande_view.html.twig',[
        'infos'=>$anonces,
      ]);
    }

    /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/vos-notions-a-enseigner", name="notion_enseigner")
     */
    public function notion_nseigner(){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonces = $repository -> findBy(['anonceur_id'=>$this->getUser()->getId()]);
     
      return $this->render('professeurs/notions.html.twig',[
        'annonces' => $anonces,
      ]);
    }

    
    /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}/formulaire-de-remplissage-des-notions", name="form_notion")
     */
    public function form_notion($id){
       
      return $this->render('professeurs/form_notion.html.twig',[
        'id' => $id,
      ]);
    }

    
     /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/{id}/notion-traitement", name="notion_form_traitement")
     */
    public function notion_traitement(Request $request,$id){
      $notions = $request->request->get('notions');
      $em = $this -> getDoctrine() -> getManager();
      $notion = new Notion();

      $notion->setNotion($notions);
      $notion->setAnnonceurId($this->getUser()->getId());
      $notion->setAnnonceId($id);
      $notion->setAccomplir('false');
      $notion->setDateNotion(new \DateTime());
       
       $em->persist($notion);
       $em->flush();

      return $this->redirectToRoute('notion_saved');

    }

    /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/1", name="notion_saved")
     */
    public function notion_saved(){
      return $this->render('professeurs/notion_saved.html.twig');
    }





}
