<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Matiere;
use App\Entity\CvAnonceur;
use App\Entity\Pourcentages;
use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Entity\AnnonceValidation;
use App\Entity\Anonce;
use App\Repository\MatiereRepository;
use App\Repository\AnnonceValidationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Form\CvType;
use Symfony\Component\String\Slugger\SluggerInterface;

 /**
  * @Route("/admin")
  */
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
       $user -> setPrenom('(Tatsumi Oga)');
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
       $user -> setPdp('...');
       $user -> setDateInscrit(new \DateTime());

       $pourcentage = new Pourcentages();

       $pourcentage->setPourcentage(10);
       $pourcentage->setDatePourcentage(new \DateTime());

       $em -> persist($user);
       $em -> persist($pourcentage);
       $em -> flush();



      return $this -> redirectToRoute('admin');
    }

     /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/", name="admin")
     */
    public function homeAdmin(){
        //pour connaitre le nombre d'annonce non certifier
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $actifs = $repository -> findBy(['actif'=>'false']);
        $compteur = 0;
        foreach($actifs as $actif){
        $compteur++;
        }

        //pour connaitre le nombre total des annonces
        $repository2 = $this -> getDoctrine() -> getRepository(Anonce::class);
        $allAnnonces = $repository2 -> findAll();
        $compteur2=0;
        foreach($allAnnonces as $all){
            $compteur2++;
        }

        //nombre total des etudiants
        $repository3 = $this -> getDoctrine() -> getRepository(User::class);
        $etuds = $repository3 -> findAll();
        $compteur3=0;
        foreach($etuds as $all){
         if($all->getRoles()[0]=='ROLE_ETUD'){
            $compteur3++;
         }
        }

        //nombre total des professeurs
        $repository3 = $this -> getDoctrine() -> getRepository(User::class);
        $profs = $repository3 -> findAll();
        $compteur4=0;
        foreach($profs as $all){
         if($all->getRoles()[0]=='ROLE_PROF'){
            $compteur4++;
         }
        }

        //nombre de demande de certification
        $repository3 = $this -> getDoctrine() -> getRepository(Commande::class);
        $coms = $repository3 -> findBy(['statut' => 1]);
        $compteur5=0;
        foreach($coms as $com){
            $compteur5++;
        }

        
        return $this -> render('admin/index.html.twig',[
            'nombre' => $compteur,
            'nombreAnnonce' => $compteur2,
            'etud' => $compteur3,
            'prof' => $compteur4,
            'coms' => $compteur5,
        ]);
    }


    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/ajout-matieres", name="ajout_matiere")
     */
    public function matiere(Request $request){
        $erreur = $request->get('erreur');
        $mess = '';
        if ($erreur && $erreur == 1) {
            $mess = 'Desoler ce cours Existe deja. vous povez juste le modifier';
          
        }
        return $this -> render('admin/matiere.html.twig',[
            'erreur' => $mess,
            'code' => 1,
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

       return $this->redirectToRoute('modifier_matiere',['mess'=>3]);

      }else {
         
          return $this -> redirectToRoute('ajout_matiere',['erreur'=>1]);
      }
      
       return new Response('hmm c\'est bizarre');
    }

    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/modifier-matiere", name="modifier_matiere")
     */
    public function modifMatiere(Request $request){
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieres = $repository -> findAll();
        $mess=0;
        if ($request->get('mess')) {
            $mess = $request->get('mess');
        }
        $message = '';
        if ($mess) {
            if($mess == 1) $message ='Modification bien effectuer';
            if($mess == 2) $message ='Suppression bien effectuer';
            if($mess == 3) $message ='Critere bien Enregistrer';
        }

        
        return $this -> render('admin/modif_matiere.html.twig',[
            'matieres'=>$matieres,
            'message'=>$message,
            'matieresF'=>'',
            'coursF'=>'',
        ]);
    }

     /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/modif-matiere", name="mod_matiere")
     */
    public function modif_matiere($id){
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieres = $repository -> findBy(['id'=>$id]);
        
        return $this -> render('admin/form_modif.html.twig',[
            'matieres'=>$matieres,
        ]);
    }

    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/update-matiere/{id}", name="update_traitement")
     */
    public function updateTraitement(Request $request,$id){
        $cours = $request->request->get('cours');
        $branche = $request->request->get('specialite');
        
        
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieres = $repository -> upToDate($id,$cours,$branche);
        
        return $this->redirectToRoute('modifier_matiere',['mess'=>1]);
    }
    //

    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/del-matiere/{id}", name="del_matiere")
     */
    public function del_matiere($id){
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieres = $repository -> deler($id);

        return $this->redirectToRoute('modifier_matiere',['mess'=>2]);
    }

     /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/search-matiere", name="search_matiere")
     */
    public function search(Request $request){
        $matiere = $request->request->get('matiere');
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieres = $repository -> findOneBy(['cours'=>$matiere]);

        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $matieress = $repository -> findAll();
        $mess=0;
        if ($request->get('mess')) {
            $mess = $request->get('mess');
        }
        $message = '';
        if ($mess) {
            if($mess == 1) $message ='Modification bien effectuer';
            if($mess == 2) $message ='Suppression bien effectuer';
            if($mess == 3) $message ='Critere bien Enregistrer';
        }

        

     return $this -> render('admin/modif_matiere.html.twig',[
            'matieresF'=>$matieres->getId(),
            'coursF'=>$matieres->getCours(),
            'matieres'=>$matieress,
            'message'=>$message,
        ]);
    }


     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/{id}/certification", name="certifier_anonce")
     */
    public function certification($id,Request $request, SluggerInterface $slugger){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $annonce = $repository -> findBy(['id'=>$id]);

        $cv = new CvAnonceur();
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                 //enregistrons l'extension dans une variable
                 $extension = $brochureFile->guessExtension();
                // creons un tableau d'extension disponible pour le fichier.
                $extensions_autorisees = array('jpg','gif','GIF', 'jpeg','JPG','JPEG','png','PNG');
                  $nameErrorFile = $slugger->slug(pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME)); //cette variable doit retenir le nom du fichier avant la verification sur l'extension
                 if (in_array($extension,$extensions_autorisees)){
            
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('cv_annonce'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    //throw new Exception("Error Processing Request", 1);

                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
               
            }else{
                /*si l'utilisateur a essayer d'envoyer un fichier different d'une image
                return $this->render('elysionne/ErrorFileExtension.html.twig', [
                    'nomFichier' =>  $nameErrorFile,
                    'extension' => $extension,
                ]);*/
                return new Response('une erreur est produit: La cause est du au fichier que vous avez essayer d\'envoyer');
            }

            $em = $this -> getDoctrine() -> getManager();
            $cv = new CvAnonceur();
            $cv->setAnonceurId($this->getUser()->getId());
            $cv->setAnonceId($id);
            $cv->setDiplome($newFilename);
            $cv->setDateEnregistrement(new \DateTime());
            $cv->setActif('false');

            $em -> persist($cv);
            $em -> flush();

            return $this->redirect($this->generateUrl('attente',['idAnonce'=>$id]));
            //return new Response($id);
          }
        
        }
        return $this -> render('admin/certification.html.twig',[
            'annonce'=>$annonce,
            'form' => $form->createView(),
        ]);
    }
       
    
  
   /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/{idAnonce}/traitement", name="attente")
     */
    public function attente($idAnonce){

        $repository = $this -> getDoctrine() -> getRepository(CvAnonceur::class);
        $annonce = $repository -> findBy(['id'=>$idAnonce]);


        return $this -> render('admin/traitement.html.twig',[
            'annonce'=>$annonce,
            
        ]);
    }

    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/nb", name="nbCom")
     */
    public function nb(Request $request){
       

        if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
            $jsonData = array();
            $idx=0;
            

            $repository = $this -> getDoctrine() -> getRepository(CvAnonceur::class);
            $annonce = $repository -> findBy(['actif'=>'false']);
            $compt = 0;

            foreach($annonce as $anon){
             $compt++;
            }

            foreach($annonce as $anon){

                $temp = array(
                  'nb' => $compt,
                );

                $jsonData[$idx++] = $temp;
            }
        
            return new JsonResponse($jsonData);
           

        }else{ return new Response('erreur ajax'); }
           
       
    }
    
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/notification", name="notification")
     */
    public function notification(){

        $repository = $this -> getDoctrine() -> getRepository(CvAnonceur::class);
        $annonce = $repository -> findBy(['actif'=>'false']);


        return $this -> render('admin/notification.html.twig',[
            'notification'=>$annonce,
            
        ]);
    }
    
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/annonce_a_certifier", name="anonce_a_certifier")
     */
    public function ac(Request $request){
        $id = $request->get('id');

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $annonce = $repository -> findBy(['id'=>$id]);


        return $this -> render('admin/visu.html.twig',[
            'anonce'=>$annonce,
            
        ]);
    }
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/been_certified", name="been_certified")
     */
    public function been_certified(Request $request){
        $id = $request->get('id');

        $repository = $this -> getDoctrine() -> getRepository(CvAnonceur::class);
        $mod = $repository -> update($id);

        $repository2 = $this -> getDoctrine() -> getRepository(Anonce::class);
        $mod2 = $repository2 -> update2($id);

       return $this->redirectToRoute('admin');
    }

 //methode pour consulter la liste des etudiants 

     /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/Etudiants", name="tout_etudiants")
     */
    public function tous_etudiants(Request $request){
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $users = $repository -> findAll();
       /* $v = false;
        foreach($users as $user){
            if(in_array('ROLE_PROF',$user->getRoles())){
              $v = true;
              $etudiants = $repository -> findAll();
            }
        }*/


        return $this -> render('admin/tout_etudiants.html.twig',[
            'users'=>$users,
            
        ]);

    }
    
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/annonce-a-valider", name="annonce_validation")
     */
    public function annonceValide(Request $request){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $T_anonces = $repository -> findBy(['actif' => 'false']);
        
       /* foreach($T_anonces as $anonce){
            $repository2 = $this -> getDoctrine() -> getRepository(Anonce::class);
            $anonces = $repository2 -> findBy(['id' => $anonce->getAnnonceId()]);
            
        }*/

        return $this->render('admin/annonce_validation.html.twig',[
         'anonces' => $T_anonces,
        ]);
    }

    
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/{id}/suprimer-annonce", name="suprime_annonce")
     */
    public function Sannonce($id){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $T_anonces = $repository -> delAnonce($id);
        
        return $this->redirectToRoute('annonce_validation');
    }

    
    /**
    * * Require ROLE_ADMIN for only this controller method.
    *
    * @IsGranted("ROLE_ADMIN")
     * @Route("/tout-les-annonces", name="tout_annonce")
     */
    public function Tannonces(){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $annonces = $repository -> findAll();

        return $this -> render('admin/tout_anonce.html.twig ',[
        'annonces' => $annonces,
        ]);
        
    }

    
    /**
    * Require ROLE_ADMIN for only this controller method.
    * @IsGranted("ROLE_ADMIN")
    * @Route("/tout-les-etudiant", name="tou_etudiants")
    */
    public function Tetudiant(){
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $etudiants = $repository -> findAll();
        
        return $this -> render('admin/tou_etudiants.html.twig',[
            'etudiants' => $etudiants,
        ]);
    }

    /**
    * Require ROLE_ADMIN for only this controller method.
    * @IsGranted("ROLE_ADMIN")
    * @Route("/tout-les-professeurs", name="tou_professeurs")
    */
    public function toutProf(){
        $repository = $this -> getDoctrine() -> getRepository(User::class);
            $etudiants = $repository -> findAll();
            $profs = $repository->findAll();
        
        return $this -> render('admin/tout_professeurs.html.twig',[
          'professeurs' => $profs,
        ]);
    }

    
    /**
    * Require ROLE_ADMIN for only this controller method.
    * @IsGranted("ROLE_ADMIN")
    * @Route("/les-contacts", name="demande_contacts")
    */
    public function demande_contact(){
        $repository = $this -> getDoctrine() -> getRepository(Commande::class);
        $contacts = $repository->findBy(['statut' => 1,'status'=>'true']);
        foreach($contacts as $contact){
            $repository = $this -> getDoctrine() -> getRepository(User::class);
            $profs = $repository->findBy(['id' => $contact -> getAnnonceurId()]);
        }

        //nous allons recuperer la valeur du % actuelle
        $repository = $this -> getDoctrine() -> getRepository(Pourcentages::class);
        $prcs = $repository->findAll();
        $prc = 10; //valeur initiale
        foreach($prcs as $pr){
            $prc = $pr -> getPourcentage(); //nous avons recuperer le % que nous allons envoyer a la vue
        } 

        return $this->render('admin/contacts.html.twig',[
            'contacts' => $contacts,
            'prc' => $prc,
        ]);
    
    }

    
     /**
    * Require ROLE_ADMIN for only this controller method.
    * @IsGranted("ROLE_ADMIN")
    * @Route("/update_prc", name="update_prc")
    */
    public function update_prc(Request $request){
        $new_prc = $request->request->get('prc');
        $repository = $this -> getDoctrine() -> getRepository(Pourcentages::class);
        $contacts = $repository->upPrc($new_prc);


        return $this->redirectToRoute('demande_contacts');
    }
    
      /** 
       * @IsGranted("ROLE_ADMIN")
       * @Route("/{id}/a_annonce-commande", name="a_commande_view")
      */
    public function commande_view($id){
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
      $anonces = $repository -> findBy(['id'=>$id]);
     


      return $this->render('admin/a_commande_view.html.twig',[
        'infos'=>$anonces,
      ]);
    }

    
     /** 
       * @IsGranted("ROLE_ADMIN")
       * @Route("/{id}/{id_annonceur}/liaison_fait.html", name="liaison_fait")
      */
      public function liaison_fait($id,$id_annonceur){
        $repository = $this -> getDoctrine() -> getRepository(Commande::class);
        $contacts = $repository->liaison($id,$id_annonceur);

        return $this->redirectToRoute('demande_contacts');
      }

       /** 
       * @IsGranted("ROLE_ADMIN")
       * @Route("/data_commande/{annonce_id}", name="more")
      */
      public function ajaxCours(Request $request,$annonce_id){
        // $annonc_id = $request->get('annonce_id');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$annonce_id,]);
         
        if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
            $jsonData = array();
            $idx=0; $boost = 0;
            if($branches){  
            foreach($branches as $bran){
               // $lesBranche = explode(',',$bran->getBranche());
               $prix_prof = ($bran->getTarifHeure() - $bran->getPourcentage()); //ici nous souscrevons le pourcentage de l'entreprise a celui du prof
                $temp = array(
                  'tarif_heure' => $bran->getTarifHeure(),
                  'pourcentage' => $bran -> getPourcentage(),
                  'prix_prof' => $prix_prof,
                );
                $jsonData[$idx++] = $temp;
            }
          }
        
            return new JsonResponse($jsonData);
           
        }else{
            return new Response('erreur ajax');
        }
    }

     /** 
       * @Route("/data_annonce/{annonce_id}", name="more2")
     */
    public function data_annonce(Request $request,$annonce_id){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$annonce_id,]);
         
        if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
            $jsonData = array();
            $idx=0; $boost = 0;
            if($branches){  
            foreach($branches as $bran){
               // $lesBranche = explode(',',$bran->getBranche());
               $prix_prof = ($bran->getTarifHeure() - $bran->getPourcentage()); //ici nous souscrevons le pourcentage de l'entreprise a celui du prof
                $temp = array(
                  'tarif_heure' => $bran->getTarifHeure(),
                  'pourcentage' => $bran -> getPourcentage(),
                  'prix_prof' => $prix_prof,
                  'cours' => $bran-> getCours(),
                  'type_cours' => $bran -> getTypeCours(),
                  'titre' => $bran -> getTitre(),
                  'parcours' => $bran -> getParcours(),
                  'methodologie' => $bran -> getMethodologie(),
                  'lieux_cours' => $bran -> getLieuCours(),
                  'pdp' => $bran -> getPhotoProfil(),
                  'cours' => $bran -> getCours(),
                );
                $jsonData[$idx++] = $temp;
            }
          }
        
            return new JsonResponse($jsonData);
           
        }else{
            return new Response('erreur ajax');
        }
    }


   /*
    1-partage de flayers
    2-collage des flayer au barbilla
    3-communication a l'eglyse
   */




}
